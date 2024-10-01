<?php

namespace App\Models;

use App\Core\PDOService;

class CountryModel extends BaseModel
{
    private string $table_name = "country";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getCountries(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

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
}
