<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Models\DeveloperModel;
use App\Services\DevelopersService;
use App\Validation\ValidationHelper;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class DeveloperController extends BaseController
{
    //* Instance creation
    public function __construct(
        private DeveloperModel $devModel,
        private DevelopersService $developersService
    ) {
        parent::__construct();
    }

    //* Get all developers (with pagination)
    public function handleGetDevelopers(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        //using custom method to validate parameters
        $this->devModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $developers = $this->devModel->getDevelopers($params);

        //renderJson and send the data
        return $this->renderJson($response, [
            "developers" => $developers
        ]);
    }

    public function handleGetDeveloperById(Request $request, Response $response, array $args): Response
    {
        $dev = $this->validateDevId($args, $request);

        //render Json and send the data
        return $this->renderJson($response, [
            "developer" => $dev,
        ]);
    }

    public function handleGetGamesByDeveloperId(Request $request, Response $response, array $args): Response
    {
        $dev = $this->validateDevId($args, $request);

        $params = $request->getQueryParams();

        $this->devModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $games = $this->devModel->getGamesByDevId($args["developer_id"]);

        $dev["games"] = $games;

        return $this->renderJson($response, [
            "developer" => $dev
        ]);
    }

    private function validateDevId($args, $request)
    {
        $this->checkIdSet($args, 'developer_id', $request);

        $dev_id = $args['developer_id'];

        $this->validateIdNum($dev_id, $request, "Developers");

        $developer = $this->devModel->getDeveloperById($dev_id);

        $this->validateObj($developer, $request, "Could not find developer with id [{$dev_id}]");

        return $developer;
    }

    public function handleCreateDeveloper(Request $request, Response $response)
    {
        $new_dev = $request->getParsedBody();
        $result = $this->developersService->createDeveloper($new_dev);
        $status = $result->isSuccess() ? HTTP_CREATED : 400;
        $payload = [];

        if ($result->isSuccess()) {
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['inserted_developer'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }
        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }

    public function handleDeleteDeveloper(Request $request, Response $response)
    {
        $body = $request->getParsedBody();

        // Delete update
        $result = $this->developersService->deleteDeveloper($body);

        $status = $result->isSuccess() ? HTTP_OK : 400;

        $payload = [];

        if ($result->isSuccess()) {
            //prepare a successful response
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['deleted_developer'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }
        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }

    public function handleUpdateDeveloper(Request $request, Response $response)
    {
        $new_dev = $request->getParsedBody();
        // Update the Update
        $result = $this->developersService->updateDeveloper($new_dev);
        $status = $result->isSuccess() ? HTTP_OK : 400;
        $payload = [];

        if ($result->isSuccess()) {
            //prepare a successful response
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['updated_developer'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }
        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }
}
