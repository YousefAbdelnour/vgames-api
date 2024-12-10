<?php

namespace App\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use LogicException;
use App\Exceptions\HttpUnauthorizedException;

class AccountMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        //if statement to check if the params have login or register
        $uri = $request->getUri()->getPath();
        if (
            str_contains($uri, '/login') ||
            str_contains($uri, '/register')
            || $uri == '/vgames-api/v2/'
        ) {
            return $handler->handle($request);
        }
        //* 1) Extract the token from header "Authorization: Bearer <JWT>"
        $auth_header = $request->getHeaderLine("Authorization");
        $jwt = str_replace("Bearer ", "", $auth_header);

        try {
            $decoded = JWT::decode($jwt, new Key(SECRET_KEY, 'HS256'));
            // permissions for admin, editor, and user
            if ($decoded->role == "user") {
                if ($request->getMethod() != 'GET') {
                    throw new HttpUnauthorizedException($request);
                }
            }
            if ($decoded->role == "editor") {
                if ($request->getMethod() != 'GET' && $request->getMethod() != 'PUT') {
                    throw new HttpUnauthorizedException($request);
                }
            }
            $request = $request->withAttribute("jwt", $decoded);
        } catch (LogicException $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        } catch (Exception $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        }
        //! DO NOT remove or change the following statements.
        $response = $handler->handle($request);
        return $response;
    }
}
