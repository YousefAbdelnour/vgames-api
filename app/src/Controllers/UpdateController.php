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
        if ($this->validatePaginationParams($params, $request)) {
            $this->update_model->setPaginationOptions($params["page"], $params["page_size"]);
        }

        // response
        return $this->renderJson($response, [
            "data" => $this->update_model->getUpdates($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetUpdateById(Request $request, Response $response, array $args): Response
    {
        if (isset($args['update_id'])) {
            if (is_numeric($args['update_id']) && (int) $args['update_id'] > 0) {
                $result = $this->update_model->getUpdateById($args['update_id']);
                if ($result == false) {
                    throw new HttpNotFoundException($request, "Could not find Update with id [{$args['update_id']}]");
                } else {
                    return $this->renderJson($response, [
                        "data" => $result
                    ], StatusCodeInterface::STATUS_OK);
                }
            }
        }
        throw new HttpBadRequestException($request, "Invalid Update id.");
    }
}
