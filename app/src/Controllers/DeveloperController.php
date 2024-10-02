<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Models\DeveloperModel;
use App\Validation\ValidationHelper;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class DeveloperController extends BaseController
{
    //* Instance creation
    public function __construct(private DeveloperModel $devModel)
    {
        parent::__construct();
    }

    //* Get all developers (with pagination)
    public function handleGetDevelopers(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        //using custom method to validate parameters
        if ($this->validatePaginationParams($params, $request)) {
            //setting the pagination options through the genreModel
            $this->devModel->setPaginationOptions(
                $this->getValidatedPaginationParams($params, $request)
            );
        }

        $developers = $this->devModel->getDevelopers($params);

        //renderJson and send the data
        return $this->renderJson($response, [
            "data" => $developers
        ]);
    }

    public function handleGetDeveloperById(Request $request, Response $response, array $args): Response
    {
        // check if ID is set
        $this->checkIdSet($args, 'developer_id', $request);

        $dev_id = $args['developer_id'];

        $this->validateIdNum($dev_id, $request, "games");

        //genre name is equal to the genre name provided in the args

        $developer = $this->devModel->getDeveloperById($dev_id);

        //if the genre doesn't exist, be transparent with the user
        $this->validateObj($developer, $request, "Could not find game with id [{$dev_id}]");

        //render Json and send the data
        return $this->renderJson($response, [
            "data" => $developer,
        ], StatusCodeInterface::STATUS_OK);
    }
}
