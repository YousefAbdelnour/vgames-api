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

        return (array) $this->paginate($sql, $query_args);
    }
    public function getCountryByName($country_Name)
    {
        $query_args = [];
        $sql = "SELECT * FROM {$this->table_name} WHERE Country_Name = :Country_Name";
        return $this->fetchSingle($sql, ["Country_Name" => $country_Name]);
    }
}
