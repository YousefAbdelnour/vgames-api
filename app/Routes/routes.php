<?php

declare(strict_types=1);

use App\Controllers\AboutController;

//* Imports for controllers
use App\Controllers\CountryController;
use App\Controllers\GameController;
use App\Controllers\GenreController;
use App\Controllers\DeveloperController;
use App\Controllers\DLCController;

use App\Controllers\ReviewController;
use App\Controllers\UpdateController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    // Routes with authentication
    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    //* ROUTE: GET/countries
    $app->get('/countries', [CountryController::class, 'handleGetCountries']);

    //* ROUTE: GET/countries/{country_id}
    $app->get('/countries/{country_Name}', [CountryController::class, 'handleGetCountryByName']);

    //* ROUTE: GET/countries/{country_id}/games
    $app->get('/countries/{country_Name}/games', [CountryController::class, 'handleGetGamesByCountryName']);

    //* ROUTE: GET/games
    $app->get('/games', [GameController::class, 'handleGetGames']);

    //* ROUTE: GET/updates
    $app->get('/updates', [UpdateController::class, 'handleGetUpdates']);

    //* ROUTE: GET/updates/{update_id}
    $app->get('/updates/{update_id}', [UpdateController::class, 'handleGetUpdateById']);

    //* ROUTE: GET/games/{game_id}
    $app->get('/games/{game_id}', [GameController::class, 'handleGetGameById']);

    //* ROUTE: GET/genres
    $app->get('/genres', [GenreController::class, 'handleGetGenres']);

    //* ROUTE: GET/genres/{genre_name}
    $app->get('/genres/{genre_name}', [GenreController::class, 'handleGetGenreByName']);

    //* ROUTE: GET/developers
    $app->get('/developers', [DeveloperController::class, 'handleGetDevelopers']);

    //* ROUTE: GET/developers/{developer_id}
    $app->get('/developers/{developer_id}', [DeveloperController::class, 'handleGetDeveloperById']);

    //* ROUTE: GET/dlc
    $app->get('/dlc', [DLCController::class, 'handleGetDLCs']);

    //* ROUTE: GET/dlc/{dlc_id}
    $app->get('/dlc/{dlc_id}', [DLCController::class, 'handleGetDLCById']);

    //* ROUTE: GET/reviews
    $app->get('/reviews', [ReviewController::class, 'handleGetReviews']);

    //* ROUTE: GET/reviews/{review_id}
    $app->get('/reviews/{review_id}', [ReviewController::class, 'handleGetReviewById']);

    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {

        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });
};
