<?php

namespace App\Services;

use App\Core\Result;
use App\Models\GameModel;
use App\Models\UpdateModel;
use App\Validation\Validator;
use DateTime;

class UpdatesService extends BaseService
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
    private $delete_rules = array(
        'id' => [
            'required',
            'integer'
        ]
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
        //validating date format
        if (isset($new_update['Date']) && !$this->isValidDate($new_update['Date'])) {
            $errors['Date'][] = "Date must be a valid date with format 'YYYY-MM-DD'";
        }

        //validate game id
        if (isset($new_update['Game_Id']) && !$this->gameModel->isValidGameId($new_update['Game_Id'])) {
            $errors['Game_Id'][] = "Could not find Game with Id [{$new_update['Game_Id']}]";
        }

        if ($errors) return Result::fail("Invalid Update Object", $errors);
        $created_id = $this->updateModel->CreateUpdate($new_update);
        $update_created = $this->updateModel->getUpdateById($created_id);
        return Result::success("Update successfully created!", $update_created);
    }

    public function deleteUpdate($update): Result
    {
        $errors = [];
        $validator = new Validator($update);
        $validator->mapFieldsRules($this->delete_rules);
        if (!$validator->validate()) {
            $errors = $validator->errors();
        } else if (!$this->updateModel->isValidUpdateId($update['id'])) {
            $errors['id'][] = "Could not find Update with id [{$update['id']}]";
        }
        if ($errors) return Result::fail("Invalid Update Id", $errors);
        $update_id = $update['id'];
        $deleted_update = $this->updateModel->getUpdateById($update_id);

        $this->updateModel->deleteUpdate($update_id);

        return Result::success('Update deleted successfully.', $deleted_update);
    }

    public function updateUpdate($new_update): Result
    {
        $put_rules = array_merge(
            array('Update_Id' => [
                'required',
                'integer',
            ]),
            $this->rules
        );

        $errors = [];
        $validator = new Validator($new_update);
        $validator->mapFieldsRules($put_rules);
        if (!$validator->validate()) {
            $errors = $validator->errors();
        }
        if (isset($new_update['Update_Id']) && !$this->updateModel->isValidUpdateId($new_update['Update_Id'])) {
            $errors['Update_Id'][] = "Could not find Update with id [{$new_update['Update_Id']}]";
        }
        //validating date format
        if (isset($new_update['Date']) && !$this->isValidDate($new_update['Date'])) {
            $errors['Date'][] = "Date must be a valid date with format 'YYYY-MM-DD'";
        }

        //validate game id
        if (isset($new_update['Game_Id']) && !$this->gameModel->isValidGameId($new_update['Game_Id'])) {
            $errors['Game_Id'][] = "Could not find Game with Id [{$new_update['Game_Id']}]";
        }
        if ($errors) return Result::fail("Invalid Update Object", $errors);
        $this->updateModel->updateUpdate($new_update);
        $updated_update = $this->updateModel->getUpdateById($new_update['Update_Id']);

        return Result::success('Update updated successfully.', $updated_update);
    }
}
