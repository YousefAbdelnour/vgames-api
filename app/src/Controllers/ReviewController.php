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
            "reviews" => $reviews,
        ]);
    }

    public function handleGetReviewById(Request $request, Response $response, array $args): Response
    {
        $review = $this->validateReviewId($args, $request);

        return $this->renderJson($response, [
            "review" => $review,
        ]);
    }

    private function validateReviewId($args, $request)
    {
        // check if ID is set
        $this->checkIdSet($args, 'review_id', $request);

        $review_id = $args['review_id'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdNum($review_id, $request, "reviews");

        $review = $this->review_model->getReviewById($review_id);

        // check if the $review obj returned by sql is present
        $this->validateObj($review, $request, "Could not find review with id [{$review_id}]");

        return $review;
    }
}
