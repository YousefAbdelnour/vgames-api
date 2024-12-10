<?php

namespace App\Models;

use App\Core\PDOService;

class PlatformModel extends BaseModel
{

    private string $table_name = "platform";

    public array $fields = ['platform_name', 'founder', 'current_owner', 'tagline', 'website', 'type', 'release_date', 'num_of_languages', 'cloud_gaming_support'];

    public string $default_sort_field = 'platform_name';


    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getPlatforms(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);
        return (array) $this->paginate($sql, $query_args);
    }

    public function getPlatformByName($platform_name): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where Platform_Name = :platform_name";
        return $this->fetchSingle($sql, ["platform_name" => $platform_name]);
    }

    private function filter(array $params, string &$sql, array &$query_args)
    {
        //*Platform_NAME
        if (isset($params['platform_name'])) {
            $sql .= ' AND Platform_Name LIKE :platform_name';
            $query_args['platform_name'] = '%' . $params['platform_name'] . '%';
        }

        //* Founder_NAME
        if (isset($params['founder'])) {
            $sql .= ' AND founder LIKE :founder';
            $query_args['founder'] = '%' . $params['founder'] . '%';
        }

        //* cloud_gaming_support
        if (isset($params['cloud_gaming_support'])) {
            $sql .= ' AND cloud_gaming_support = :cloud_gaming_support';
            $query_args['cloud_gaming_support'] = $params['cloud_gaming_support'];
        }

        //* Release Date
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

        //* Num of languages
        if (isset($params['min_num_languages']) && isset($params['max_num_languages'])) {
            $sql .= ' AND num_of_languages BETWEEN :min_num_languages AND :max_num_languages ';
            $query_args['min_num_languages'] = $params['min_num_languages'];
            $query_args['max_num_languages'] = $params['max_num_languages'];
        } else if (isset($params['max_num_languages'])) {
            $sql .= ' AND num_of_languages <= :max_num_languages';
            $query_args['max_num_languages'] = $params['max_num_languages'];
        } else if (isset($params['min_num_languages'])) {
            $sql .= ' AND num_of_languages >= :min_num_languages';
            $query_args['min_num_languages'] = $params['min_num_languages'];
        } else if (isset($params['num_of_languages'])) {
            $sql .= ' AND num_of_languages = :num_of_languages';
            $query_args['num_of_languages'] = $params['num_of_languages'];
        }
    }
}
