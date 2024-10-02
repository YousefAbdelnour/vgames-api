<?php

namespace App\Controllers;

use App\Models\DLCModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DLCController extends BaseController
{

    public function __construct(private DLCModel $dlcModel)
    {
        parent::__construct();
    }

    public function handleGetDLCs(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->dlcModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // games
        $dlc = $this->dlcModel->getDLCs($params);

        // response
        return $this->renderJson($response, [
            "data" => $dlc,
        ]);
    }

    public function handleGetDLCById(Request $request, Response $response, array $args): Response
    {
        // check if ID is set
        $this->checkIdSet($args, 'dlc_id', $request);

        $dlc_id = $args['dlc_id'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdNum($dlc_id, $request, "dlc");

        $dlc = $this->dlcModel->getDLCById($dlc_id);

        // check if the $game obj returned by sql is present
        $this->validateObj($dlc, $request, "Could not find DLC with id [{$dlc_id}]");

        return $this->renderJson($response, [
            "data" => $dlc,
        ]);
    }
}
