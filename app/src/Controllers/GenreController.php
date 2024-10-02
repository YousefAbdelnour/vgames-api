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

    //* Get all genres (with pagination)
    public function handleGetGenres(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        //using custom method to validate parameters
        if ($this->validatePaginationParams($params, $request)) {
            //setting the pagination options through the getValidatedPaginationParams 
            $this->genreModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));
        }
        //renderJson and send the data
        return $this->renderJson($response, [
            "data" => $this->genreModel->getGenres($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetGenreByName(Request $request, Response $response, array $args): Response
    {
        //check if the genre_name is set
        if (!isset($args['genre_name'])) {
            throw new HttpBadRequestException($request, "Invalid genre name.");
        }

        //genre name is equal to the genre name provided in the args
        $genre_name = $args['genre_name'];
        $genre = $this->genreModel->getGenreByName($genre_name);

        //if the genre doesn't exist, be transparent with the user
        if ($genre === false) {
            throw new HttpNotFoundException($request, "Could not find genre titled: [{$genre_name}]");
        }

        //render Json and send the data
        return $this->renderJson($response, [
            "data" => $genre,
        ], StatusCodeInterface::STATUS_OK);
    }
}
