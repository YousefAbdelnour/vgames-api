<?php

namespace App\Models;

use App\Core\PDOService;

class DeveloperModel extends BaseModel
{
    private string $table_name = "developer";

    public array $fields = ['dev_id', 'dev_name', 'founder', 'headquarters', 'type', 'parent', 'prog_lang', 'number_games_made', 'founded_date', 'number_of_employees'];
    public string $default_sort_field = 'Dev_Id';
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getDevelopers(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        //* Filtering Parameters
        $this->filter($params, $sql, $query_args);
        //* Sorting
        $this->sort($params, $sql, $this);

        return $this->paginate($sql, $query_args);
    }

    public function getDeveloperById($dev_id)
    {
        // Selecting specific name
        $sql = "SELECT * FROM {$this->table_name} WHERE Dev_Id = :dev_id";

        // Returning the single instance of the name
        $developer = $this->fetchSingle($sql, ["dev_id" => $dev_id]);
        return $developer;
    }

    //? Filter

    private function filter(array $params, string &$sql, array &$query_args)
    {
        //*DEV_ID
        if (isset($params['dev_id'])) {
            $sql .= ' AND Dev_Id = :dev_id';
            $query_args['dev_id'] = $params['dev_id'];
        }

        //*DEV_NAME
        if (isset($params['dev_name'])) {
            $sql .= ' AND Dev_Name LIKE :dev_name';
            $query_args['dev_name'] = '%' . $params['dev_name'] . '%';
        }

        //* Founder_NAME
        if (isset($params['founder'])) {
            $sql .= ' AND founder LIKE :founder';
            $query_args['founder'] = '%' . $params['founder'] . '%';
        }

        //* Number Games Made
        if (isset($params["min_games_made"]) && isset($params["max_games_made"])) {
            $sql .= " AND number_games_made BETWEEN :min_games_made AND :max_games_made";
            $query_args['min_games_made'] = $params['min_games_made'];
            $query_args['max_games_made'] = $params['max_games_made'];
        } else if (isset($params['min_games_made'])) {
            $sql .= ' AND number_games_made >= :min_games_made';
            $query_args['min_games_made'] = $params['min_games_made'];
        } else if (isset($params['max_games_made'])) {
            $sql .= ' AND number_games_made <= :max_games_made';
            $query_args['max_games_made'] = $params['max_games_made'];
        } else if (isset($params['number_games_made'])) {
            $sql .= ' AND number_games_made = :number_games_made';
            $query_args['number_games_made'] = $params['number_games_made'];
        }


        //* Founded Date
        if (isset($params['from_founded_date']) && isset($params['to_founded_date'])) {
            $sql .= ' AND founded_date BETWEEN :from_founded_date AND :to_founded_date ';
            $query_args['from_founded_date'] = $params['from_founded_date'];
            $query_args['to_founded_date'] = $params['to_founded_date'];
        } else if (isset($params['to_founded_date'])) {
            $sql .= ' AND founded_date <= :to_founded_date';
            $query_args['to_founded_date'] = $params['to_founded_date'];
        } else if (isset($params['from_founded_date'])) {
            $sql .= ' AND founded_date >= :from_founded_date';
            $query_args['from_founded_date'] = $params['from_founded_date'];
        } else if (isset($params['founded_date'])) {
            $sql .= ' AND founded_date = :founded_date';
            $query_args['founded_date'] = $params['founded_date'];
        }
    }

    //! POST
    // public function insertDeveloper(array $newDev): mixed
    // {
    //     //$sql = "INSERT INTO {$this->table_name} VALUES ()";
    //     $last_id = $this->insert($this->table_name, $newDev);
    //     return $last_id;
    // }
}
