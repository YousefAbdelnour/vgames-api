<?php

namespace App\Controllers;

use App\Exceptions\HttpInvalidPaginationParamsException;
use App\Models\GameModel;
use App\Validation\ValidationHelper;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class GameController extends BaseController
{

    public function __construct(private GameModel $game_model)
    {
        parent::__construct();
    }

    public function handleGetGames(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // pagination
        $this->validatePaginationParams($params, $request);
        $this->game_model->setPaginationOptions($params["page"], $params["page_size"]);

        // response
        return $this->renderJson($response, [
            "data" => $this->game_model->getGames($params),
        ], StatusCodeInterface::STATUS_OK);
    }

    public function handleGetGameById(Request $request, Response $response, array $args): Response
    {

        if (!isset($args['game_id'])) {
            throw new HttpBadRequestException($request, "Invalid game id.");
        }

        $game_id = $args['game_id'];

        $game = $this->game_model->getGameById($game_id);

        if ($game === false) {
            throw new HttpNotFoundException($request, "Could not find game with id [{$game_id}]");
        }

        return $this->renderJson($response, [
            "data" => $game,
        ], StatusCodeInterface::STATUS_OK);
    }
}
