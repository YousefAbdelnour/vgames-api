<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AppSettings;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'Video games API';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome! This is a Web service that provides information about video games.',
            'authors' => ["Yousef Abdelnour", "Rowan Lajoie", "Denis Voronov"],
            'resources' => [
                "games" => [
                    "GET" => [
                        "/games",
                        "/games/{game_id}",
                        "/games/{game_id}/reviews",
                        "/games/{game_id}/platforms",
                    ],
                    "POST" => [
                        "/games",
                    ],
                    "DELETE" => [
                        "/games",
                    ],
                    "PUT" => [
                        "/games"
                    ]
                ],
                "reviews" => [
                    "GET" => [
                        "/reviews",
                        "/reviews/{review_id}"
                    ]
                ],
                "countries" => [
                    "GET" => [
                        "/countries",
                        "/countries/{country_Name}",
                        "/counties/{country_Name}/games"
                    ]
                ],
                "updates" => [
                    "GET" => [
                        "/updates",
                        "/updates/{update_id}",
                    ],
                    "POST" => [
                        "/updates",
                    ],
                    "DELETE" => [
                        "/updates",
                    ],
                    "PUT" => [
                        "/updates"
                    ]
                ],
                "genres" => [
                    "GET" => [
                        "/genres",
                        "/genres/{genre_name}",
                        "/genres/{genre_name}/games"
                    ]
                ],
                "DLCs" => [
                    "GET" => [
                        "/dlcs",
                        "/dlcs/{dlc_id}"
                    ]
                ],
                "developers" => [
                    "GET" => [
                        "/developers",
                        "/developers/{developer_id}",
                        "/developers/{developer_id}/games",
                    ],
                    "POST" => [
                        "/developers",
                    ],
                    "DELETE" => [

                        "/developers",
                    ],
                    "PUT" => [
                        "/developers"
                    ]
                ],
                "platforms" => [
                    "GET" => [
                        "/platforms",
                        "/platforms/{platform_name}"
                    ]
                ]
            ]
        );

        return $this->renderJson($response, $data);
    }
}
