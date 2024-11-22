<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EloController
{
    public function handleCalculateElo(Request $request, Response $response) {
        $body = $request->getParsedBody();
    }
}
