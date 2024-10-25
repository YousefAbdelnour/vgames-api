<?php

namespace App\Models;

use App\Core\PDOService;

class GenreModel extends BaseModel
{
    private string $table_name = "genre";

    public array $fields = ['genre_name', 'description', 'popularity_score', 'target_audience', 'average_rating', 'average_game_length'];

    public string $default_sort_field = 'genre_name';

    public function __construct(PDOService $pdo, private CountryModel $country_model)
    {
        parent::__construct($pdo);
    }

    public function getGenres(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name}";
        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);
        return $this->paginate($sql, $query_args);
    }

    public function getGenreByName($name)
    {
        // Selecting specific name
        $sql = "SELECT * FROM {$this->table_name} WHERE Genre_Name = :genre_name";

        // Returning the single instance of the name
        $genre = $this->fetchSingle($sql, ["genre_name" => $name]);
        return $genre;
    }

    private function filter(array $params, string &$sql, array &$query_args)
    {


        //*Genre_NAME
        if (isset($params['genre_name'])) {
            $sql .= ' AND Genre_Name LIKE :genre_name';
            $query_args['genre_name'] = '%' . $params['genre_name'] . '%';
        }

        //*Description_Name
        if (isset($params['description'])) {
            $sql .= ' AND `description` LIKE :description';
            $query_args['description'] = '%' . $params['description'] . '%';
        }

        //*popularity score
        if (isset($params['popularity_score'])) {
            $sql .= ' AND popularity_score = :popularity_score';
            $query_args['popularity_score'] = $params['popularity_score'];
        }

        //*rating
        if (isset($params['min_rating']) && isset($params['max_rating'])) {
            $sql .= ' AND average_rating BETWEEN :min_rating AND :max_rating ';
            $query_args['min_rating'] = $params['min_rating'];
            $query_args['max_rating'] = $params['max_rating'];
        } else if (isset($params['max_rating'])) {
            $sql .= ' AND average_rating <= :max_rating';
            $query_args['max_rating'] = $params['max_rating'];
        } else if (isset($params['min_rating'])) {
            $sql .= ' AND average_rating >= :min_rating';
            $query_args['min_rating'] = $params['min_rating'];
        } else if (isset($params['average_rating'])) {
            $sql .= ' AND average_rating = :average_rating';
            $query_args['average_rating'] = $params['average_rating'];
        }

        //* Target Audience
        if (isset($params['min_target_age']) && isset($params['max_target_age'])) {
            $sql .= ' AND target_audience BETWEEN :min_target_age AND :max_target_age ';
            $query_args['min_target_age'] = $params['min_target_age'];
            $query_args['max_target_age'] = $params['max_target_age'];
        } else if (isset($params['max_target_age'])) {
            $sql .= ' AND num_of_languages <= :max_target_age';
            $query_args['max_target_age'] = $params['max_target_age'];
        } else if (isset($params['min_target_age'])) {
            $sql .= ' AND num_of_languages >= :min_target_age';
            $query_args['min_target_age'] = $params['min_target_age'];
        } else if (isset($params['target_audience'])) {
            $sql .= ' AND target_audience = :target_audience';
            $query_args['target_audience'] = $params['target_audience'];
        }
    }

    public function getGamesByGenreName($genre_name): mixed
    {
        $genre = $this->getGenreByName($genre_name);

        $games_query = "SELECT * FROM Game WHERE Genre_Name = :genre_name";

        $games = $this->paginate($games_query, ["genre_name" => $genre_name]);

        foreach ($games["data"] as $i => $game) {
            // can't inject $developer_model due to circular injection. genre and dev models would depend on each other, causing an error.
            $dev_sql = "SELECT * FROM developer WHERE Dev_Id = :dev_id";

            $developer = $this->fetchSingle($dev_sql, ["dev_id" => $game["Developer_Id"]]);

            $games["data"][$i]["developer"] = $developer;
            $games["data"][$i]["country"] = $this->country_model->getCountryByName($game["Country_Name"]);

            $sql_dlc = "SELECT * FROM dlc WHERE game_id = :game_id";
            $games["data"][$i]["DLCs"] = $this->fetchAll($sql_dlc, ["game_id" => $game["Game_Id"]]);
        }

        $genre["games"] = $games;

        return $genre;
    }

    public function isValidGenreName(string $genre_name)
    {
        return $this->count('SELECT * FROM genre WHERE genre_name = :genre_name', ['genre_name' => $genre_name]) != 0;
    }
}
