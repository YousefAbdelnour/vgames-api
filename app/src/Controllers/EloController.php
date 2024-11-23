<?php

namespace App\Controllers;

use App\Helpers\EloHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EloController extends BaseController
{
    public function handleCalculateElo(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $player_rating = $body['player_rating'];
        $rounds = $body['games_result'];
        $round_number = 0;
        $rounds_table = [];
        foreach ($rounds as $round) {
            $round_number++;
            EloHelper::calculateRoundElo( $rounds_table, $request, $round, $player_rating, $round_number);
        }
        return $this->renderJson($response, $rounds_table);
    }
}
