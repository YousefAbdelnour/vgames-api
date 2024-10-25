<?php

namespace App\Services;

use App\Core\Result;
use App\Models\GameModel;
use App\Models\UpdateModel;
use App\Validation\Validator;

class UpdatesService
{
    private $rules = array(
        'Limited_Time_Event' => [
            'required',
            'integer',
            array('min', 0),
            array('max', 1),
        ],
        'Game_Id' => [
            'required',
            'integer',
            array('min', 0)
        ],
        'Date' => [
            'required',
            array('dateFormat', 'Y-m-d')
        ],
        'Description' => [
            'required',
            array('lengthMax', 2000)
        ],
        'Version_Number' => [
            'required',
            array('lengthMax', 255)
        ],
        'Update_Size' => [
            'required',
            'numeric',
            array('min', 0.1)
        ],
        'New_Features' => [
            'required',
            array('lengthMax', 5000)
        ],
    );

    public function __construct(private UpdateModel $updateModel, private GameModel $gameModel) {}

    public function createUpdate($new_update)
    {
        $errors = [];
        $validator = new Validator($new_update);
        $validator->mapFieldsRules($this->rules);
        if (!$validator->validate()) {
            $errors = $validator->errors();
        }

        //validate game id
        if (!$this->gameModel->isValidGameId($new_update['Game_Id'])) {
            $errors['Game_Id'][] = "Could not find Game with Id [{$new_update['Game_Id']}]";
        }

        if ($errors) return Result::fail("Invalid Update Object", $errors);
        $update_created = $this->updateModel->CreateUpdate($new_update);
        return Result::success("Update successfully created!", $update_created);
    }
}
