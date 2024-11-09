<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Helpers\LogHelper as Logger;

class LoggerMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        Logger::logAccess($request);
        $response = $handler->handle($request);
        return $response;
    }
}
