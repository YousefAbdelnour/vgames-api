<?php

namespace App\Models;

use App\Core\PDOService;

class DLCModel extends BaseModel
{

    private string $table_name = "dlc";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getDLCs(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

        return (array) $this->paginate($sql, $query_args);
    }

    public function getDLCById($dlc_id): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where DLC_id = :dlc_id";
        return $this->fetchSingle($sql, ["dlc_id" => $dlc_id]);
    }
}

