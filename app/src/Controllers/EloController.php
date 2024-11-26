<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidEloArgumentException;
use App\Helpers\EloHelper;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EloController extends BaseController
{
    public function handleCalculateElo(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        if (!isset($body['player_rating']) || !isset($body['game_results'])) throw new HttpInvalidEloArgumentException($request, "Fields are missing, please check that all the fields are there and try again.");
        $player_rating = $body['player_rating'];
        if (!is_numeric($player_rating)) throw new HttpInvalidEloArgumentException($request);
        $rounds = $body['game_results'];
        $round_number = 0;
        $rounds_table = [];
        $winning_score = 0;
        $total_score = 0;
        $total_elo_change = 0;
        foreach ($rounds as $round) {
            $round_number++;
            EloHelper::calculateRoundElo($rounds_table, $request, $round, $player_rating, $round_number, $winning_score, $total_score, $total_elo_change);
        }
        $summary = array(
            "initial_rating" => $player_rating,
            "overall_score" => "{$winning_score}/{$total_score}",
            "rating_change" => round($total_elo_change, 2),
            "final_rating" => $player_rating + $total_elo_change,
            "rounds_summary" => $rounds_table
        );
        return $this->renderJson($response, $summary);
    }
}
