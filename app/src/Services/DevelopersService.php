<?php

namespace App\Services;

use App\Core\Result;
use App\Models\DeveloperModel;

class DevelopersService extends BaseService
{

    //TODO: Declare the validation rules for the collection resource
    public function __construct(private DeveloperModel $developerModel) {}

    public function createDeveloper(array $new_dev): Result
    {
        return Result::fail("Random failure message", ["Missing id", "Invalid email address"]);
    }


    public function updateDeveloper(): Result
    {
        return Result::success("Developer updated successfully.");
    }


    public function deleteDeveloper(): Result
    {
        return Result::success("Developer deleted successfully.");
    }
}
