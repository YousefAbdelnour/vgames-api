<?php

namespace App\Controllers;

use App\Models\PlatformModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PlatformController extends BaseController
{

    public function __construct(private PlatformModel $platformModel)
    {
        parent::__construct();
    }

    public function handleGetPlatforms(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->platformModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // games
        $platforms = $this->platformModel->getPlatforms($params);

        // response
        return $this->renderJson($response, [
            "data" => $platforms,
        ]);
    }

    public function handleGetPlatformByName(Request $request, Response $response, array $args): Response
    {
        // check if ID is set
        $this->checkIdSet($args, 'platform_name', $request);

        $platform_name = $args['platform_name'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdStr($platform_name, $request, "platform");

        $platform = $this->platformModel->getPlatformByName($platform_name);

        // check if the $game obj returned by sql is present
        $this->validateObj($platform, $request, "Could not find game with id [{$platform_name}]");

        return $this->renderJson($response, [
            "data" => $platform,
        ]);
    }
}
