<?php

namespace App\Controllers;

use App\Models\CountryModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        $this->country_Model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // response
        return $this->renderJson($response, [
            "countries" => $this->country_Model->getCountries($params),
        ]);
    }

    public function handleGetCountryByName(Request $request, Response $response, array $args): Response
    {
        $country = $this->validateCountryName($args, $request);

        return $this->renderJson($response, [
            "country" => $country
        ]);
    }

    public function handleGetGamesByCountryName(Request $request, Response $response, array $args): Response
    {
        $country = $this->validateCountryName($args, $request);

        $params = $request->getQueryParams();

        $this->country_Model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $country = $this->country_Model->getGamesByCountryName($country["Country_Name"]);

        return $this->renderJson($response, ["country" => $country]);
    }

    private function validateCountryName($args, $request)
    {
        $this->checkIdSet($args, 'country_Name', $request);

        $country_Name = $args['country_Name'];

        $this->validateIdStr($country_Name, $request, "Countries");

        $country = $this->country_Model->getCountryByName($country_Name);

        $this->validateObj($country, $request, "Could not find country named [{$country_Name}]");
        return $country;
    }
}
