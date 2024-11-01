<?php

namespace App\Services;

use App\Validation\Validator;

use App\Core\Result;
use App\Models\DeveloperModel;

class DevelopersService extends BaseService
{
    public $rules = array(
        'Dev_Name' => [
            'required',
            array('lengthMax', 255)
        ],
        'Founder' => [
            'required',
            array('lengthMax', 255)
        ],
        'Headquarters' => [
            'required',
            array('lengthMax', 255)
        ],
        'Type' => [
            'required',
            array('lengthMax', 255)
        ],
        'Parent' => [
            'required',
            'integer',
        ],
        'Prog_Lang' => [
            'required',
            'integer',
        ],
        'Number_Games_Made' => [
            'required',
            'integer',
        ],
        'Founded_Date' => [
            'required'
        ],
        'Number_Of_Employees' => [
            'required',
            'integer',
            ['min', 1]
        ],
    );
    public function __construct(private DeveloperModel $developerModel) {}

    public function createDeveloper($new_dev): Result
    {
        $errors = [];

        $validator = new Validator($new_dev);

        $validator->mapFieldsRules($this->rules);

        if (!$validator->validate()) {
            $errors = $validator->errors();
        }
        if (isset($new_dev['Founded_Date']) && !$this->isValidDate($new_dev['Founded_Date'])) {
            $errors['Founded_Date'][] = "Date must be a valid date with format 'YYYY-MM-DD'";
        }
        if ($errors) return Result::fail("Invalid Developer Object", $errors);
        //* Creating Developer_Id
        $created_id = $this->developerModel->CreateDeveloper($new_dev);
        $developer_created = $this->developerModel->getDeveloperById($created_id);
        return Result::success("Developer successfully created!", $developer_created);
    }


    public function updateDeveloper($dev_updated): Result
    {
        $errors = [];
        $validator = new Validator($dev_updated);
        $validator->mapFieldsRules($this->rules);

        if (!$validator->validate()) {
            $errors = $validator->errors();
        }

        if (isset($dev_updated['Founded_Date']) && !$this->isValidDate($dev_updated['Founded_Date'])) {
            $errors['Founded_Date'][] = "Date must be a valid date with format 'YYYY-MM-DD'";
        }
        if ($errors) return Result::fail("Invalid Developer Object", $errors);
        $this->developerModel->updateDeveloper($dev_updated);
        $updated_dev = $this->developerModel->getDeveloperById($dev_updated['Dev_Id']);

        return Result::success("Developer updated successfully.", $updated_dev);
    }


    public function deleteDeveloper($deleted_dev): Result
    {
        $errors = [];
        $validator = new Validator($deleted_dev);

        if (isset($deleted_dev['id']) && !$this->developerModel->isValidDevId($deleted_dev['id'])) {
            $errors['id'][] = "Could not find developer with id [{$deleted_dev['id']}]";
        };

        $dev_id = $deleted_dev['id'];

        $deleted_developer = $this->developerModel->getDeveloperById($dev_id);

        if ($errors) {
            return Result::fail("Invalid Developer Object", $errors);
        } else {
            $this->developerModel->deleteDeveloper($dev_id);
            return Result::success('Developer deleted successfully.', $deleted_developer);
        };
    }
}
