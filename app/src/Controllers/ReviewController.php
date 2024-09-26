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

        $this->review_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // reviews
        $reviews = $this->review_model->getReviews($params);

        // response
        return $this->renderJson($response, [
            "data" => $reviews,
        ]);
    }
}
