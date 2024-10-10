<?php

namespace App\Models;

use App\Core\PDOService;

class PlatformModel extends BaseModel
{

    private string $table_name = "platform";
    public string $default_sort_field = 'Release_Date';
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getPlatforms(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        return (array) $this->paginate($sql, $query_args);
    }

    public function getPlatformByName($platform_name): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where Platform_Name = :platform_name";
        return $this->fetchSingle($sql, ["platform_name" => $platform_name]);
    }
}
