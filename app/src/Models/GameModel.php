<?php

namespace App\Models;

use App\Core\PDOService;

class GameModel extends BaseModel
{

    private string $table_name = "game";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getGames(array $params): array
    {
        $query_args = [];

        //TODO: filtering, sorting

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

        return (array) $this->paginate($sql, $query_args);
    }

    public function getGameById($game_id): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where game_id = :game_id";
        return $this->fetchSingle($sql, ["game_id" => $game_id]);
    }

    public function getReviewsByGameId($game_id): array
    {
        $query_args = [];

        //TODO: same fileting logic for get /reviews

        $sql = "SELECT * FROM review WHERE game_id = :game_id";
        return (array) $this->paginate($sql, ["game_id" => $game_id]);
    }
}
