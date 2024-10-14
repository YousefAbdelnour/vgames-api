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

    public function handleGetGamesByDeveloperId(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();

        $dev = $this->validateDevId($args, $request);

        $this->devModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $games = $this->devModel->getGamesByDevId($args["developer_id"]);

        $this->validateObj($games, $request, "Could not find games from the Developer [{$args['developer_id']}]");

        $dev["games"] = (array) $games;

        return $this->renderJson($response, ["Developer" => $dev]);
    }


    private function validateDevId($args, $request)
    {
        $this->checkIdSet($args, 'developer_id', $request);

        $dev_id = $args['developer_id'];

        $this->validateIdNum($dev_id, $request, "Developers");

        $developer = $this->devModel->getDeveloperById($dev_id);

        $this->validateObj($developer, $request, "Could not find game with id [{$dev_id}]");

        return $developer;
    }

    //! POST
    // public function handleCreateDevelopers(Request $request, Response $response): Response
    // {
    //     // 1) Retrieve the info about the new players to be created from
    //     // the request body.
    //     $newDev = $request->getParsedBody();
    //     dd(data: $newDev);
    //     // Create the new players
    //     $result = $this->developersService->createDeveloper($newDev);
    //     $payload = [];
    //     if ($result->isSuccess()) {
    //         //Prepare a successful response
    //         $payload["success"] = true;
    //         $payload["status"] = 201;
    //         $payload["message"] = $result->getData();
    //     } else {
    //         //Prepare a failure response

    //         $payload["success"] = false;
    //         $payload["status"] = 400;
    //         $payload["message"] = $result->getMessage();
    //     }

    //     return $this->renderJson($response, $payload, 201);
    // }
}
