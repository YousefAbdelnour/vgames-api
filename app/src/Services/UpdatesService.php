<?php

namespace App\Services;

use App\Core\Result;
use App\Models\UpdateModel;

class UpdatesService
{
    public function __construct(private UpdateModel $updateModel) {}

    public function createUpdate($new_update)
    {

        //! verify the update to be created using validtron


        $update_created = $this->updateModel->CreateUpdate($new_update);
        return Result::success("Update successfully created!", $update_created);
    }
}
