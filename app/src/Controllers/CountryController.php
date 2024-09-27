<?php

namespace App\Controllers;

use App\Models\CountryModel;
use Fig\Http\Message\StatusCodeInterface;
use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class CountryController extends BaseController
{
    public function __construct(private CountryModel $country_Model)
    {
        parent::__construct();
    }

    public function handleGetCountries(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // pagination. if it is requested then set, otherwise keep going
        if ($this->validatePaginationParams($params, $request)) {
            $this->country_Model->setPaginationOptions($params["page"], $params["page_size"]);
        }

        // response
        return $this->renderJson($response, [
            "data" => $this->country_Model->getCountries($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetCountryByName(Request $request, Response $response, array $args): Response
    {
        if (isset($args['country_Name'])) {
            if (is_string($args['country_Name'])) {
                $result = $this->country_Model->getCountryByName($args['country_Name']);
                if ($result == false) {
                    throw new HttpNotFoundException($request, "Could not find Update with id [{$args['country_Name']}]");
                } else {
                    return $this->renderJson($response, [
                        "data" => $result
                    ], StatusCodeInterface::STATUS_OK);
                }
            }
        }
        throw new HttpBadRequestException($request, "Invalid Country id.");
    }
}
