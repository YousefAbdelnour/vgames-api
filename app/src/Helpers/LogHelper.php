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
        $logger->pushHandler(new StreamHandler(APP_LOGS_PATH . 'access.log', Level::Debug));
        $log_record = "User accessed this service successfully! IP address: " . $_SERVER['REMOTE_ADDR'];
        $extra_info = $request->getQueryParams();
        $logger->info($log_record, $extra_info);
    }

    public static function getErrorLogger(): Logger{
        $logger = new Logger("ERROR");
        $logger->pushHandler(new StreamHandler(APP_LOGS_PATH . 'error.log', Level::Error));
        return $logger;
    }
}
