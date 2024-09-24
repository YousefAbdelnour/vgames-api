<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Models\ReviewModel;
use App\Validation\ValidationHelper;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReviewController extends BaseController
{

    public function __construct(private ReviewModel $review_model)
    {
        parent::__construct();
    }

    public function handleGetReviews(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // validating pagination params
        if (isset($params["page"]) && isset($params["page_size"])) {

            // checks if parameters are positive digits
            if (!ValidationHelper::areValidPaginationParams($params)) {
                throw new HttpInvalidPaginationParamsException($request);
            }
            $this->review_model->setPaginationOptions($params["page"], $params["page_size"]);
        }

        return $this->renderJson($response, [
            "data" => $this->review_model->getReviews($params),
        ], StatusCodeInterface::STATUS_OK);
    }
}
