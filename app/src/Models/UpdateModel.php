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
        $result['New_Features'] = explode(',',$result["New_Features"]);
        return $result;
    }

    private function parseNewFeatures($data){
        foreach ($data as &$row){
            $row['New_Features'] = explode(',',$row["New_Features"]);
        }
        return $data;
    }
}
