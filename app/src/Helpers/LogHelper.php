<?php

namespace App\Helpers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogHelper
{

    public static function logAccess(Request $request)
    {
        //* 1) Instantiate the logger
        $logger = new Logger("ACCESS");
        //* 2) push a stream handler
        $logger->pushHandler(new StreamHandler(APP_LOGS_PATH . 'access.log', Level::Info));
        $method = $request->getMethod();
        $uri = (string)$request->getUri();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $queryParams = $request->getQueryParams();
        $logMessage = sprintf(
            "Access log: Method: %s | URI: %s | IP: %s | Query Params: %s",
            $method,
            $uri,
            $ipAddress,
            json_encode($queryParams)
        );
        $logger->info($logMessage);
    }

    public static function logError($log_info)
    {
        $logger = new Logger("NOTICE");
        $logger->pushHandler(new StreamHandler(APP_LOGS_PATH . 'error.log', Level::Notice));
        $logger->notice($log_info);
    }
}
