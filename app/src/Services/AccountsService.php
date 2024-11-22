<?php

namespace App\Services;

use App\Core\PasswordTrait;
use App\Core\Result;
use App\Models\AccountModel;
use App\Validation\Validator;

class AccountsService extends BaseService
{
    use PasswordTrait;
    public function __construct(private AccountModel $accountModel) {}

    private $rules = array(
        'first_name' => [
            'required',
            array('lengthMax', 100)
        ],
        'last_name' => [
            'required',
            array('lengthMax', 150)
        ],
        'email' => [
            'required',
            array('lengthMax', 150),
            'email'
        ],
        'password' => [
            'required',
            array('lengthMax', 255)
        ],
        'role' => [
            'required',
            array('in', ['admin', 'user'])
        ],
    );

    public function createAccount($new_account)
    {
        $errors = [];
        $validator = new Validator($new_account);
        $validator->mapFieldsRules($this->rules);
        if (!$validator->validate()) {
            $errors = $validator->errors();
        }
        if ($errors) return Result::fail("Invalid Account", $errors);
        $new_account['password'] = $this->cryptPassword($new_account['password']);
        $created_id = $this->accountModel->createAccount($new_account);
        $account_created = $this->accountModel->getAccountById($created_id);
        return Result::success("Account successfully created!", $account_created);
    }

    public function accessAccount(){
        
    }
}
