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
        $this->genreModel->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $genres = $this->genreModel->getGenres($params);

        //renderJson and send the data
        return $this->renderJson($response, [
            "genres" => $genres
        ]);
    }

    public function handleGetGenreByName(Request $request, Response $response, array $args): Response
    {
        //check if the genre_name is set
        $this->checkIdSet($args, 'genre_name', $request);

        //genre name is equal to the genre name provided in the args
        $genre_name = $args['genre_name'];

        $this->validateIdStr($genre_name, $request, "Genres");

        $genre = $this->genreModel->getGenreByName($genre_name);

        //if the genre doesn't exist, be transparent with the user
        $this->validateObj($genre, $request, "Could not find genre titled: [{$genre_name}]");


        //render Json and send the data
        return $this->renderJson($response, [
            "genre" => $genre,
        ]);
    }
}
