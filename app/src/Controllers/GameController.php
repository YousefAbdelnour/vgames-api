<?php

namespace App\Controllers;

use App\Helpers\ClientHelper;
use App\Models\GameModel;
use App\Services\GamesService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GameController extends BaseController
{

    public function __construct(private GameModel $game_model, private GamesService $games_service)
    {
        parent::__construct();
    }

    public function handleGetAchievementsByGame(Request $request, Response $response, array $args): Response
    {
        $game = $this->validateGameId($args, $request);
        $game_name = strtolower(str_replace(' ', '-', $game['Name']));
        $rawg_uri = "https://api.rawg.io/api/games";
        $api_key = "25fe58f136e544d9bd1247d6b312ac0f";
        $client = new ClientHelper([
            'query' => [
                "key" => $api_key,
                "search" => $game_name,
                "search_exact" => true
            ]
        ]);
        $data = $client->invokeUri($rawg_uri);
        if ($data['count'] == 0) {
            dd($game);
            throw new HttpNotFoundException($request, "Game Could not be found In Rawg's Database");
        }
        $game_id = $data['results'][0]['id'];
        $achievements_Uri = "https://api.rawg.io/api/games/{$game_id}/achievements";
        $client->setOptions([
            'query' => [
                "key" => $api_key
            ]
        ]);
        $achievements = $client->invokeUri($achievements_Uri);
        if ($achievements['count'] == 0) {
            throw new HttpNotFoundException($request, "Game Achievements Could not be found in Rawg's Database");
        }
        return $this->renderJson($response, [
            "game" => $game,
            "achievements" => $achievements,
        ]);
    }

    public function handleGetGames(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->game_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // games
        $games = $this->game_model->getGames($params);

        // free games
        $client = new ClientHelper();
        $free_games = $client->invokeUri('https://www.freetogame.com/api/games');

        // response
        return $this->renderJson($response, [
            "games" => $games,
            "free_games" => $free_games
        ]);
    }

    public function handleGetGameById(Request $request, Response $response, array $args): Response
    {
        $game = $this->validateGameId($args, $request);

        return $this->renderJson($response, [
            "game" => $game,
        ]);
    }

    public function handleGetReviewsByGameId(Request $request, Response $response, array $args): Response
    {
        $game = $this->validateGameId($args, $request);

        // PAGINATION
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->game_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // get reviews for the game with given ID
        $payload = $this->game_model->getReviewsByGame($game);

        return $this->renderJson($response, [
            "game" => $payload,
        ]);
    }

    public function handleGetPlatformsByGameId(Request $request, Response $response, array $args): Response
    {
        $game = $this->validateGameId($args, $request);

        // PAGINATION
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->game_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $payload = $this->game_model->getPlatformsByGame($game);

        return $this->renderJson($response, [
            "game" => $payload,
        ]);
    }

    public function handleCreateGame(Request $request, Response $response): Response
    {
        // get request body
        $new_game = $request->getParsedBody();

        $result = $this->games_service->createGame($new_game);

        $status = $result->isSuccess() ? HTTP_CREATED : 400;

        if ($result->isSuccess()) {
            // success response
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['inserted_game'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }

        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }

    public function handleUpdateGame(Request $request, Response $response): Response
    {
        // get request body
        $new_game = $request->getParsedBody();

        $result = $this->games_service->updateGame($new_game);

        $status = $result->isSuccess() ? HTTP_OK : 400;

        if ($result->isSuccess()) {
            // success response
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['updated_game'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }

        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }

    public function handleDeleteGame(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $result = $this->games_service->deleteGame($body);

        $status = $result->isSuccess() ? HTTP_OK : 400;

        if ($result->isSuccess()) {
            // success response
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['deleted_game'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }

        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }

    private function validateGameId($args, $request)
    {
        // check if ID is set
        $this->checkIdSet($args, 'game_id', $request);

        $game_id = $args['game_id'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdNum($game_id, $request, "games");

        $game = $this->game_model->getGameById($game_id);

        // check if the $game obj returned by sql is present
        $this->validateObj($game, $request, "Could not find game with id [{$game_id}]");

        return $game;
    }
}
