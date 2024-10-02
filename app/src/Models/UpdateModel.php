<?php

namespace App\Models;

use App\Core\PDOService;

class UpdateModel extends BaseModel
{
    private string $table_name = "game_update";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getUpdates(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

        $this->filterAndSort($params, $sql, $query_args);

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

    public static function parseNewFeatures($data)
    {
        foreach ($data as &$row) {
            $row['New_Features'] = explode(',', $row["New_Features"]);
        }
        return $data;
    }

    public static function filterAndSort(array $params, String &$sql, array &$query_args)
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

        //* sorting
        if (isset($params['sort_by']) && isset($params['order'])) {
            $sql .= ' ORDER BY Update_Size ' . strtoupper($params['order']);
        } else if (isset($params['sort_by'])) {
            $sql .= ' ORDER BY Update_Size ASC ';
        } else if (isset($params['order'])) {
            $sql .= ' ORDER BY Update_Id ' . strtoupper($params['order']);
        } else {
            $sql .= ' ORDER BY Update_Id ASC';
        }
    }
}
