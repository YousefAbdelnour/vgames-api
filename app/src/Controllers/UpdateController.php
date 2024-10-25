<?php

namespace App\Controllers;

use App\Models\UpdateModel;
use App\Services\UpdatesService;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateController extends BaseController
{
    public function __construct(private UpdateModel $update_model, private UpdatesService $updatesService)
    {
        parent::__construct();
    }

    public function handleGetUpdates(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // pagination. if it is requested then set, otherwise keep going
        $this->update_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // response
        return $this->renderJson($response, [
            "updates" => $this->update_model->getUpdates($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetUpdateById(Request $request, Response $response, array $args): Response
    {
        $this->checkIdSet($args, 'update_id', $request);
        $update_id = $args['update_id'];

        $this->validateIdNum($update_id, $request, "Updates");

        $update = $this->update_model->getUpdateById($update_id);

        $this->validateObj($update, $request, "Could not find update with id [{$update_id}]");

        return $this->renderJson($response, [
            "update" => $update
        ]);
    }

    public function handleCreateUpdate(Request $request, Response $response)
    {
        // 1) Retrieve info about the new players to be created from the request body
        $new_update = $request->getParsedBody();
        // dd($new_update);
        // Create the new Update
        $result = $this->updatesService->createUpdate($new_update);
        $status = $result->isSuccess() ? HTTP_CREATED : 400;
        $payload = [];

        //prepare a successful response
        if ($result->isSuccess()) {
            //status
            $payload['Status'] = $status;
            //success
            $payload['Success'] = true;
            //inserted id
            $payload['Inserted Id'] = $result->getData();
        } else {
            //status
            $payload['Status'] = $status;
            //success
            $payload['Success'] = false;
            //errors
            $payload['Errors'] = $result->getData();
        }
        //message
        $payload['Message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }
}
