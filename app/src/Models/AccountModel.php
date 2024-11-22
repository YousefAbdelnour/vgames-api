<?php

namespace App\Models;

use App\Core\PDOService;

class AccountModel extends BaseModel
{
    private string $table_name = "ws_users";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function createAccount($new_account)
    {
        return $this->insert($this->table_name, $new_account);
    }

    public function getAccountById($id)
    {
        $sql = "SELECT * FROM {$this->table_name} WHERE user_id = :user_id";
        $result = $this->fetchSingle($sql, ["user_id" => $id]);
        if ($result == false) return false;
        return $result;
    }
}
