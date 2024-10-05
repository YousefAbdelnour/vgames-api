<?php

namespace App\Models;

use App\Core\PDOService;

class GameModel extends BaseModel
{

    private string $table_name = "game";

    public function __construct(PDOService $pdo, private CountryModel $country_model, private DeveloperModel $developer_model, private GenreModel $genre_model)
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

    public function getReviewsByGameId($game): array
    {
        $gameInfo = $this->getInfoAboutGame($game);

        $sql = "SELECT * FROM review WHERE game_id = :game_id";

        $reviews = (array) $this->paginate($sql, ["game_id" => $game["Game_Id"]]);

        return [
            "country" => $gameInfo["country"],
            "developer" => $gameInfo["developer"],
            "genre" => $gameInfo["genre"],
            "reviews" => $reviews
        ];
    }

    private function getInfoAboutGame($game)
    {
        $info["developer"] = $this->developer_model->getDeveloperById($game["Developer_Id"]);
        $info["country"] = $this->country_model->getCountryByName($game["Country_Name"]);
        $info["genre"] = $this->genre_model->getGenreByName($game["Genre_Name"]);
        return $info;
    }
}
