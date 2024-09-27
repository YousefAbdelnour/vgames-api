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
                $params["page"],
                $params["page_size"]
            );
        }
        //renderJson and send the data
        return $this->renderJson($response, [
            "data" => $this->devModel->getDevelopers($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetDeveloperById(Request $request, Response $response, array $args): Response
    {
        //check if the developer_id is set
        if (!isset($args['developer_id'])) {
            throw new HttpBadRequestException($request, "Invalid dev id.");
        }

        //genre name is equal to the genre name provided in the args
        $dev_id = $args['developer_id'];
        $developer = $this->devModel->getDeveloperById($dev_id);

        //if the genre doesn't exist, be transparent with the user
        if ($developer === false) {
            throw new HttpNotFoundException($request, "Could not find developer with id: [{$dev_id}]");
        }

        //render Json and send the data
        return $this->renderJson($response, [
            "data" => $developer,
        ], StatusCodeInterface::STATUS_OK);
    }
}
