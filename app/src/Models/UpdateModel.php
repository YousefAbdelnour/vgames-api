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

        return (array) $this->paginate($sql, $query_args);
    }

    public function getUpdateById($update_id)
    {
        $query_args = [];
        $sql = "SELECT * FROM {$this->table_name} WHERE update_id = :update_id";
        return $this->fetchSingle($sql, ["update_id" => $update_id]);
    }
}
