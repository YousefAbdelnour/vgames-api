<?php

namespace App\Models;

use App\Core\PDOService;

class CountryModel extends BaseModel
{
    private string $table_name = "country";

    public array $fields = ['country_name', 'most_played_game_id', 'development_companies', 'most_popular_genre', 'average_age', 'average_internet_speed', 'Language'];

    public string $default_sort_field = 'country_name';

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getCountries(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);

        $result = $this->paginate($sql, $query_args);
        $result['data'] = $this->parseDevelopment_Companies($result['data']);
        return $result;
    }
    public function getCountryByName($country_Name)
    {
        $sql = "SELECT * FROM {$this->table_name} WHERE Country_Name = :Country_Name";
        $result = $this->fetchSingle($sql, ["Country_Name" => $country_Name]);

        //* Parsing the Development_Companies field
        $result['Development_Companies'] = explode(',', $result['Development_Companies']);
        return $result;
        // return $this->fetchSingle($sql, ["Country_Name" => $country_Name]);
    }

    public function getGamesByCountryName($country_Name): array
    {
        $country = $this->getCountryByName($country_Name);

        $game_sql = <<<SQL
        SELECT gm.*
        FROM game gm
        WHERE gm.Country_Name = :Country_Name
        SQL;

        $games = $this->paginate($game_sql, ["Country_Name" => $country_Name]);

        $dev_sql = <<<SQL
        SELECT d.*
        FROM developer d
        JOIN game gm ON gm.Developer_Id = d.Dev_Id
        WHERE gm.Country_Name = :Country_Name
        SQL;

        $devs = $this->fetchAll($dev_sql, ["Country_Name" => $country_Name]);

        $genre_sql = <<<SQL
        SELECT g.*
        FROM genre g
        JOIN game gm ON gm.Genre_Name = g.Genre_Name
        WHERE gm.Country_Name = :Country_Name
        SQL;

        $genres = $this->fetchAll($genre_sql, ["Country_Name" => $country_Name]);

        return array(
            "country" => $country,
            "games" => $games,
            "developer" => $devs,
            "genre" => $genres
        );
    }

    private function parseDevelopment_Companies($data)
    {
        foreach ($data as &$row) {
            $row['Development_Companies'] = explode(',', $row["Development_Companies"]);
        }
        return $data;
    }

    public static function filter(array $params, String &$sql, array &$query_args)
    {
        if (isset($params['language'])) {
            $sql .= ' AND Language LIKE CONCAT(:language, "%")';
            $query_args['language'] = $params['language'];
        }
        if (isset($params['min_internet_speed']) && isset($params['max_internet_speed'])) {
            $sql .= ' AND Average_Internet_Speed BETWEEN :min_internet_speed AND :max_internet_speed ';
            $query_args['min_internet_speed'] = $params['min_internet_speed'];
            $query_args['max_internet_speed'] = $params['max_internet_speed'];
        } else if (isset($params['min_internet_speed'])) {
            $sql .= ' AND Average_Internet_Speed >= :min_internet_speed';
            $query_args['min_internet_speed'] = $params['min_internet_speed'];
        } else if (isset($params['max_internet_speed'])) {
            $sql .= ' AND Average_Internet_Speed <= :max_internet_speed';
            $query_args['max_internet_speed'] = $params['max_internet_speed'];
        }
        if (isset($params['min_age']) && isset($params['max_age'])) {
            $sql .= ' AND Average_Age BETWEEN :min_age AND :max_age ';
            $query_args['min_age'] = $params['min_age'];
            $query_args['max_age'] = $params['max_age'];
        } else if (isset($params['min_age'])) {
            $sql .= ' AND Average_Age >= :min_age';
            $query_args['min_age'] = $params['min_age'];
        } else if (isset($params['max_age'])) {
            $sql .= ' AND Average_Age <= :max_age';
            $query_args['max_age'] = $params['max_age'];
        }
        if (isset($params['most_popular_genre'])) {
            $sql .= ' AND Most_Popular_Genre LIKE :most_popular_genre';
            $query_args['most_popular_genre'] = '%' . $params['most_popular_genre'] . '%';
        }
        if (isset($params['development_companies'])) {
            $sql .= ' AND Development_Companies LIKE :development_companies';
            $query_args['development_companies'] = '%' . $params['development_companies'] . '%';
        }
    }
}
