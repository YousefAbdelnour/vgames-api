<?php

namespace App\Models;

use App\Core\PDOService;

class UpdateModel extends BaseModel
{
    private string $table_name = "game_update";

    public array $fields = ['update_id', 'update_type', 'limited_time_event', 'game_id', 'date', 'description', 'version_number', 'update_size', 'new_features'];

    public string $default_sort_field = 'update_id';

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getUpdates(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);

        $result = $this->paginate($sql, $query_args);

        //parsing Features
        $result['data'] = $this->parseNewFeatures($result['data']);
        return $result;
    }

    public function getUpdateById($update_id)
    {
        $query_args = [];
        $sql = "SELECT * FROM {$this->table_name} WHERE update_id = :update_id";
        $result = $this->fetchSingle($sql, ["update_id" => $update_id]);

        //* Parsing the New Features Column
        $result['New_Features'] = explode(',', $result["New_Features"]);
        return $result;
    }

    public function CreateUpdate(array $new_updates)
    {
        return $this->insert($this->table_name, $new_updates);
    }

    public static function parseNewFeatures($data)
    {
        foreach ($data as &$row) {
            $row['New_Features'] = explode(',', $row["New_Features"]);
        }
        return $data;
    }

    public static function filter(array $params, String &$sql, array &$query_args)
    {
        if (isset($params["limited_time_event"])) {
            //* TODO: parse 0 and 1 to true and false
            $sql .= " AND limited_time_event = :limited_time_event ";
            $query_args['limited_time_event'] = $params['limited_time_event'];
        }
        if (isset($params['until_date']) && isset($params['from_date'])) {
            $sql .= ' AND Date BETWEEN :until_date AND :from_date ';
            $query_args['until_date'] = $params['until_date'];
            $query_args['from_date'] = $params['from_date'];
        } else if (isset($params['until_date'])) {
            $sql .= ' AND Date <= :until_date';
            $query_args['until_date'] = $params['until_date'];
        } else if (isset($params['from_date'])) {
            $sql .= ' AND Date >= :from_date';
            $query_args['from_date'] = $params['from_date'];
        }
        if (isset($params['min_update_size']) && isset($params['max_update_size'])) {
            $sql .= ' AND Update_Size BETWEEN :min_update_size AND :max_update_size ';
            $query_args['min_update_size'] = $params['min_update_size'];
            $query_args['max_update_size'] = $params['max_update_size'];
        } else if (isset($params['max_update_size'])) {
            $sql .= ' AND Update_Size <= :max_update_size';
            $query_args['max_update_size'] = $params['max_update_size'];
        } else if (isset($params['min_update_size'])) {
            $sql .= ' AND Update_Size >= :min_update_size';
            $query_args['min_update_size'] = $params['min_update_size'];
        }
        if (isset($params['description'])) {
            $sql .= ' AND Description LIKE :description';
            $query_args['description'] = '%' . $params['description'] . '%';
        }
        if (isset($params['version_number'])) {
            $sql .= ' AND Version_Number LIKE CONCAT(:version_number, "%")';
            $query_args['version_number'] = $params['version_number'];
        }
    }
}
