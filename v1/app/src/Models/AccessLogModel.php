<?php

namespace App\Models;

use App\Core\PDOService;

class AccessLogModel extends  BaseModel
{

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function insertLog($log): mixed
    {
        return $this->insert("ws_log", $log);
    }
}
