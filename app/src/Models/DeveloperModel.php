<?php

namespace App\Models;

use App\Core\PDOService;

class DeveloperModel extends BaseModel
{
    private string $table_name = "developer";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getDevelopers(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name}";

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
}
