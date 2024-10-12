<?php

namespace App\Models;

use App\Core\PDOService;

class GenreModel extends BaseModel
{
    private string $table_name = "genre";

    public array $fields = ['genre_name', 'description', 'popularity_score', 'target_audience', 'average_rating', 'average_game_length'];

    public string $default_sort_field = 'average_rating';

    public function __construct(PDOService $pdo)
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

    public function getGamesByGenreName($genre_name): array
    {
        $genre = $this->getGenreByName($genre_name);

        $games_query = <<<SQL
        SELECT *
        FROM Game
        WHERE Genre_Name = :genre_name
        SQL;

        $games = $this->paginate($games_query, ["genre_name" => $genre_name]);

        $dev_query = <<<SQL
        SELECT d.*
        FROM Developer d
        JOIN Game g ON g.Developer_Id = d.Dev_Id
        WHERE g.Genre_Name = :genre_name
        SQL;

        $devs = $this->fetchAll($dev_query, ["genre_name" => $genre_name]);

        $dlc_query = <<<SQL
        SELECT c.*
        FROM dlc c
        JOIN Game g ON g.game_id = c.game_id
        WHERE g.Genre_name = :genre_name
        SQL;

        $dlc = $this->fetchAll($dlc_query, ["genre_name" => $genre_name]);

        $country_query = <<<SQL
        SELECT co.*
        FROM genre ge
        JOIN country co ON ge.Genre_Name = co.Most_Popular_Genre
        WHERE co.Most_Popular_Genre = :genre_name
        SQL;

        $country = $this->fetchAll($country_query, ["genre_name" => $genre_name]);

        return array(
            "genre" => $genre,
            "games" => $games,
            "developers" => $devs,
            "dlc" => $dlc,
            "country" => $country,
        );
    }
}
