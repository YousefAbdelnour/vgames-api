<?php

namespace App\Helpers;

use App\Exceptions\HttpInvalidEloArgumentException;
use Exception;

class EloHelper
{
    public static function calculateRoundElo(array &$rounds_table, $request, $round, $player_rating, $round_number, &$winning_score, &$total_score, &$total_elo_change)
    {
        $opp_rating = $round["opponent_rating"];
        if (!is_numeric($opp_rating))  throw new HttpInvalidEloArgumentException($request);
        $match_result = 0;
        switch (strtolower($round["score"])) {
            case 'win':
                $match_result = 1;
                break;
            case 'lose':
                $match_result = 0;
                break;
            case 'draw':
                $match_result = 0.5;
                break;
            default:
                throw new HttpInvalidEloArgumentException($request);
        }
        $winning_score += $match_result;
        $total_score++;
        $elo_change =  round(20 * ($match_result - 1 / (pow(10, ($opp_rating - $player_rating) / 400) + 1)), 2);
        $total_elo_change += $elo_change;
        $round_row = array(
            "game_no" => $round_number,
            "opponent_rating" => $opp_rating,
            "score" => $match_result,
            "rating_change" => $elo_change
        );
        array_push($rounds_table, $round_row);
        return $rounds_table;
    }
}
