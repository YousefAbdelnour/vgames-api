<?php

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use \Nyholm\Psr7\Factory\Psr17Factory;

class ContentNegotiationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $contentType = $request->getHeaderLine("Accept");
        $acceptableContentTypes = ["application/json"];

        if (!in_array($contentType, $acceptableContentTypes)) {
            $psr17Factory = new Psr17Factory();
            $Errored_response = $psr17Factory->createResponse(415, "Unacceptable media type.");
            $response_error_body = array(
                "Code" => 415,
                "Message" => "Unacceptable media type.",
                "Description" => "The content type that was requested is not available. Please use Application/json as accepted media type.",
            );
            $Errored_response->getBody()->write(json_encode($response_error_body));
            return $Errored_response->withHeader("content-type", "application/json");
        } else {
            $response = $handler->handle($request);
            return $response;
        }
    }
}
