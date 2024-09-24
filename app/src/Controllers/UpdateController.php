<?php

namespace App\Controllers;

use App\Models\UpdateModel;
use Fig\Http\Message\StatusCodeInterface;
use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateController extends BaseController
{
    public function __construct(private UpdateModel $update_model)
    {
        parent::__construct();
    }

    public function handleGetUpdates(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // pagination. if it is requested then set, otherwise keep going
        if ($this->validatePaginationParams($params, $request)) {
            $this->update_model->setPaginationOptions($params["page"], $params["page_size"]);
        }

        // response
        return $this->renderJson($response, [
            "data" => $this->update_model->getUpdates($params),
        ], StatusCodeInterface::STATUS_OK);
    }
}
