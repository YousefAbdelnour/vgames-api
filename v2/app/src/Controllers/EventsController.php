<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Core\AppSettings;

class EventsController extends BaseController
{
    private $clientId;
    private $accessToken;

    public function __construct(
        private AppSettings $appSettings
    ) {
        // hidden credentials from env.php
        $this->clientId = $this->appSettings->get("client_id");
        $this->accessToken = $this->appSettings->get("access_token");
    }

    public function handleGetEvents(Request $request, Response $response): Response
    {
        $igdbUri = "https://api.igdb.com/v4/events";

        $query = "fields id, name, description, start_time, end_time, time_zone, live_stream_url, slug, games; limit 5;";

        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Client-ID' => $this->clientId,
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $responseApi = $client->post($igdbUri, [
                'body' => $query,
            ]);
            $events = json_decode($responseApi->getBody(), true);

            //indexing each element to avoid losing track of which item we're modifying in the events arrays
            foreach ($events as $index => $event) {
                $eventGames = [];
                //to avoid years of loading, we're only fetching the first 5 games announced at each event
                $gamesToProcess = array_slice($event['games'], 0, 5);

                foreach ($gamesToProcess as $gameId) {
                    //using our fetching games by id method below to process each game id listed in the events
                    $eventGames[] = $this->fetchGameDetails($gameId);
                }
                //each events games are set to our array of games
                $event['games'] = $eventGames;
                //changing random int values into proper date foramt
                if (isset($event['start_time'])) {
                    $event['start_time'] = date("Y-m-d H:i:s", $event['start_time']);
                } else {
                    $event['start_time'] = "Unspecified";
                }

                if (isset($event['end_time'])) {
                    $event['end_time'] = date("Y-m-d H:i:s", $event['end_time']);
                } else {
                    $event['end_time'] = "Unspecified";
                }

                // Reassign the updated event back to the events array
                $events[$index] = $event;
            }

            return $this->renderJson($response, [
                'events' => $events,
            ], 200);
        } catch (HttpBadRequestException $e) {
            return $this->renderJson($response, [
                'error' => 'Failed to fetch events: ' . $e->getMessage(),
            ], 500);
        }
    }

    //this method grabs the game ID and uses the "where id =" aspect of the IGDB API and finds the game's name to be able to return it as a part of the events
    private function fetchGameDetails(int $gameId): array
    {
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Client-ID' => $this->clientId,
                //passing the authorization key
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
        ]);
        //parameters for finding the target game name with the ID
        $gameQuery = "fields name; where id = {$gameId};";

        try {
            //the target URI where we find the game's ID
            $responseGame = $client->post("https://api.igdb.com/v4/games", [
                'body' => $gameQuery,
            ]);

            $game = json_decode($responseGame->getBody(), true);

            return [
                'id' => $gameId,
                //grabbing the name of the game at the 0 index (because we only need the first result)
                'name' => $game[0]['name'],
            ];
        } catch (HttpBadRequestException $e) {
            return [
                'id' => $gameId,
                'name' => "Unknown Game",
            ];
        }
    }
}
