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
        if (isset($params["Limited_Time_Event"])) {
            //* TODO: parse 0 and 1 to true and false
            $sql .= " AND Limited_Time_Event = :Limited_Time_Event ";
            $query_args['Limited_Time_Event'] = $params['Limited_Time_Event'];
        }
        if (isset($params['Until_Date']) && isset($params['From_Date'])) {
            $sql .= ' AND Date BETWEEN :Until_Date AND :From_Date ';
            $query_args['Until_Date'] = $params['Until_Date'];
            $query_args['From_Date'] = $params['From_Date'];
        } else if (isset($params['Until_Date'])) {
            $sql .= ' AND Date <= :Until_Date';
            $query_args['Until_Date'] = $params['Until_Date'];
        } else if (isset($params['From_Date'])) {
            $sql .= ' AND Date >= :From_Date';
            $query_args['From_Date'] = $params['From_Date'];
        }
        if (isset($params['Min_Update_Size']) && isset($params['Max_Update_Size'])) {
            $sql .= ' AND Update_Size BETWEEN :Min_Update_Size AND :Max_Update_Size ';
            $query_args['Min_Update_Size'] = $params['Min_Update_Size'];
            $query_args['Max_Update_Size'] = $params['Max_Update_Size'];
        } else if (isset($params['Max_Update_Size'])) {
            $sql .= ' AND Update_Size <= :Max_Update_Size';
            $query_args['Max_Update_Size'] = $params['Max_Update_Size'];
        } else if (isset($params['Min_Update_Size'])) {
            $sql .= ' AND Update_Size >= :Min_Update_Size';
            $query_args['Min_Update_Size'] = $params['Min_Update_Size'];
        }
        if (isset($params['Description'])) {
            $sql .= ' AND Description LIKE :Description';
            $query_args['Description'] = '%' . $params['Description'] . '%';
        }
        if (isset($params['Version_Number'])) {
            $sql .= ' AND Version_Number LIKE CONCAT(:Version_Number, "%")';
            $query_args['Version_Number'] = $params['Version_Number'];
        }
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

    private function parseNewFeatures($data)
    {
        foreach ($data as &$row) {
            $row['New_Features'] = explode(',', $row["New_Features"]);
        }
        return $data;
    }
}
