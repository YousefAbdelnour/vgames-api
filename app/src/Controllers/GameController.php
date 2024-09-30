<?php

namespace App\Controllers;

use App\Models\GameModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GameController extends BaseController
{

    public function __construct(private GameModel $game_model)
    {
        parent::__construct();
    }

    public function handleGetGames(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->game_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        // games
        $games = $this->game_model->getGames($params);

        // response
        return $this->renderJson($response, [
            "data" => $games,
        ]);
    }

    public function handleGetGameById(Request $request, Response $response, array $args): Response
    {
        // check if ID is set
        $this->checkIdSet($args, 'game_id', $request);

        $game_id = $args['game_id'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdNum($game_id, $request, "games");

        $game = $this->game_model->getGameById($game_id);

        // check if the $game obj returned by sql is present
        $this->validateObj($game, $request, "Could not find game with id [{$game_id}]");

        return $this->renderJson($response, [
            "data" => $game,
        ]);
    }

    public function handleGetPlatformsByGameId(Request $request, Response $response, array $args): Response
    {
        // check if ID is set
        $this->checkIdSet($args, 'game_id', $request);

        $game_id = $args['game_id'];

        // validate ID, in this case it must be a positive number (function checks if the ID is composed of digits only)
        $this->validateIdNum($game_id, $request, "games");

        $game = $this->game_model->getGameById($game_id);

        // check if the $game obj returned by sql is present
        $this->validateObj($game, $request, "Could not find game with id [{$game_id}]");

        // PAGINATION
        $params = $request->getQueryParams();

        // returns an empty array if no pagination parameters were set
        $this->game_model->setPaginationOptions($this->getValidatedPaginationParams($params, $request));

        $platforms = $this->game_model->getPlatformsByGameId($game_id);

        return $this->renderJson($response, [
            "game" => ["game" => $game, "platforms" => $platforms],
        ]);
    }
}
