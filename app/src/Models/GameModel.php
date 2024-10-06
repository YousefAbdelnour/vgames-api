<?php

namespace App\Models;

use App\Core\PDOService;

class GameModel extends BaseModel
{

    private string $table_name = "game";
    public array $fields = ['game_id', 'developer_id', 'genre_name', 'name', 'founder', 'release_date', 'country_name', 'esrb', 'price', 'number_of_players', 'avg_rating'];

    public string $default_sort_field = 'game_id';

    public function __construct(PDOService $pdo, private CountryModel $country_model, private DeveloperModel $developer_model, private GenreModel $genre_model)
    {
        parent::__construct($pdo);
    }

    public function getGames(array $params): array
    {
        $query_args = [];

        $sql = <<<SQL
        SELECT g.*, IFNULL(ROUND(AVG(r.rating), 1), 0) AS avg_rating
            FROM {$this->table_name} g
                LEFT JOIN review r
                    ON g.game_id = r.game_id
                        GROUP BY g.game_id, g.name
                            HAVING 1
        SQL;

        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);

        $games =  (array) $this->paginate($sql, $query_args);

        return $games;
    }

    private function filter(array $params, String &$sql, array &$query_args)
    {
        // GENRE
        if (isset($params["genre_name"])) {
            $sql .= " AND genre_name = :genre_name ";
            $query_args['genre_name'] = $params['genre_name'];
        }

        //  RELEASE DATE
        if (isset($params['from_release_date']) && isset($params['to_release_date'])) {
            $sql .= ' AND release_date BETWEEN :from_release_date AND :to_release_date ';
            $query_args['from_release_date'] = $params['from_release_date'];
            $query_args['to_release_date'] = $params['to_release_date'];
        } else if (isset($params['to_release_date'])) {
            $sql .= ' AND release_date <= :to_release_date';
            $query_args['to_release_date'] = $params['to_release_date'];
        } else if (isset($params['from_release_date'])) {
            $sql .= ' AND release_date >= :from_release_date';
            $query_args['from_release_date'] = $params['from_release_date'];
        } else if (isset($params['release_date'])) {
            $sql .= ' AND release_date = :release_date';
            $query_args['release_date'] = $params['release_date'];
        }

        // NAME
        if (isset($params['name'])) {
            $sql .= ' AND name LIKE :name';
            $query_args['name'] = '%' . $params['name'] . '%';
        }

        // COUNTRY NAME
        if (isset($params['country_name'])) {
            $sql .= ' AND country_name LIKE :country_name';
            $query_args['country_name'] = '%' . $params['country_name'] . '%';
        }

        // FOUNDER
        if (isset($params['founder'])) {
            $sql .= ' AND founder LIKE :founder';
            $query_args['founder'] = '%' . $params['founder'] . '%';
        }

        // AVERAGE RATING
        if (isset($params["min_avg_rating"]) && isset($params["max_avg_rating"])) {
            $sql .= " AND avg_rating BETWEEN :min_avg_rating AND :max_avg_rating ";
            $query_args['min_avg_rating'] = $params['min_avg_rating'];
            $query_args['max_avg_rating'] = $params['max_avg_rating'];
        } else if (isset($params["min_avg_rating"])) {
            $sql .= " AND avg_rating >= :min_avg_rating";
            $query_args['min_avg_rating'] = $params['min_avg_rating'];
        } else if (isset($params["max_avg_rating"])) {
            $sql .= " AND avg_rating <= :max_avg_rating";
            $query_args['max_avg_rating'] = $params['max_avg_rating'];
        } else if (isset($params['avg_rating'])) {
            $sql .= " AND avg_rating = :avg_rating";
            $query_args['avg_rating'] = $params['avg_rating'];
        }
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

    public function getPlatformsByGameId($game): array
    {
        $sql = <<<SQL
        SELECT * FROM platform p, platform_game pg
                WHERE pg.game_id = :game_id
                    AND pg.platform_name = p.platform_name
        SQL;

        $gameInfo = $this->getInfoAboutGame($game);

        $platforms = (array) $this->paginate($sql, ["game_id" => $game["Game_Id"]]);

        return [
            "country" => $gameInfo["country"],
            "developer" => $gameInfo["developer"],
            "genre" => $gameInfo["genre"],
            "platforms" => $platforms
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
