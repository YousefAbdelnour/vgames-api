<?php

namespace App\Models;

use App\Core\PDOService;

class ReviewModel extends BaseModel
{
    private string $table_name = "review";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getReviews(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";

        return (array) $this->paginate($sql, $query_args);
    }
}
