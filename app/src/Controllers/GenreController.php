<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Models\GenreModel;
use App\Validation\ValidationHelper;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class GenreController extends BaseController
{
    //* Instance creation
    public function __construct(private GenreModel $genreModel)
    {
        parent::__construct();
    }

    //* Get all genres
    public function handleGetGenres(Request $request, Response $response): Response
    {

        $params = $request->getQueryParams();

        if (isset($params["page"]) && isset($params["page_size"])) {
            if (!ValidationHelper::areValidPaginationParams($params)) {
                throw new HttpInvalidPaginationParamsException($request);
            }
            $this->genreModel->setPaginationOptions(
                $params["page"],
                $params["page_size"]
            );
        }
        return $this->renderJson($response, [
            "data" => $this->genreModel->getGenres($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetGenreByName(Request $request, Response $response, array $args): Response
    {

        if (!isset($args['genre_name'])) {
            throw new HttpBadRequestException($request, "Invalid genre name.");
        }

        $genre_name = $args['genre_name'];

        $genre = $this->genreModel->getGenreByName($genre_name);

        if ($genre === false) {
            throw new HttpNotFoundException($request, "Could not find genre titled: [{$genre_name}]");
        }

        return $this->renderJson($response, [
            "data" => $genre,
        ], StatusCodeInterface::STATUS_OK);
    }
}
