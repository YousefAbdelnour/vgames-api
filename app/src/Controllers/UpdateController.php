<?php

namespace App\Controllers;

use App\Models\UpdateModel;
use Fig\Http\Message\StatusCodeInterface;
use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

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
}
