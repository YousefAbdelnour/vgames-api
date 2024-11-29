<?php

namespace App\Middleware;

use App\Models\AccessLogModel;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Helpers\LogHelper as Logger;
use App\Models\AccountModel;

class LoggerMiddleware implements MiddlewareInterface
{
    public function __construct(private AccessLogModel $accessModel, private AccountModel $accountModel) {}
    public function process(Request $request, RequestHandler $handler): Response
    {
        Logger::logAccess($request);
        $jwt = $request->getAttribute("jwt");

        if ($jwt) {
            $user = $this->accountModel->getAccountByEmail($jwt->email);
            $this->accessModel->insertLog([
                "email" =>  $jwt->email,
                "user_action" => $request->getMethod() . " " . $request->getUri()->getPath(),
                "logged_at" => date('Y-m-d H:i:s'),
                "user_id" => $user["user_id"]
            ]);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
