<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpInvalidIdException;
use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Exceptions\HttpInvalidSortingArgumentException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class BaseController
{

    public function __construct() {}
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);

        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    protected function validatePaginationParams($params, $request): bool
    {
        // validating pagination params
        if (isset($params["page"])) {
            // checks if parameters are positive digits
            if (!ValidationHelper::isValidPageNumber($params)) {
                throw new HttpInvalidPaginationParamsException($request);
            }
            //if pagination is requested and is good
            return true;
        }
        if (isset($params["page_size"])) {
            // checks if parameters are positive digits
            if (!ValidationHelper::isValidPageSize($params)) {
                throw new HttpInvalidPaginationParamsException($request);
            }
            //if pagination is requested and is good
            return true;
        }
        return false;
    }

    protected function getValidatedPaginationParams($params, $request)
    {
        $paginationParams = [];

        foreach ($params as $key => $value) {
            if ($key === "page" || $key === "page_size") {
                if (ValidationHelper::isValidPaginationParameter($params, $key, $request)) {
                    $paginationParams[$key] = (int) $value;
                }
            }
        }

        return $paginationParams;
    }

    protected function checkIdSet($params, string $field_name, $request, string $err_msg = "A valid ID must be provided.")
    {
        if (!isset($params[$field_name])) {
            throw new HttpBadRequestException($request, $err_msg);
        }
    }

    protected function validateIdNum($id, $request, string $collection)
    {
        if (!ctype_digit($id)) {
            throw new HttpInvalidIdException($request, "ID for {$collection} must be a number.");
        }
    }

    protected function validateIdStr($id, $request, string $collection)
    {
        if (!ctype_alpha($id)) {
            throw new HttpInvalidIdException($request, "ID for {$collection} must be composed of letters only.");
        }
    }

    protected function validateObj($obj, $request, string $err_msg)
    {
        if ($obj === false) {
            throw new HttpNotFoundException($request, $err_msg);
        }
    }
}
