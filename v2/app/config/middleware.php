<?php

declare(strict_types=1);

use App\Core\CustomErrorHandler;
use App\Middleware\AccountMiddleware;
use App\Middleware\ContentNegotiationMiddleware;
use App\Middleware\LoggerMiddleware;
use Slim\App;
use App\Helpers\LogHelper as Logger;

return function (App $app) {
    // Add your middleware here.
    $app->addMiddleware(new ContentNegotiationMiddleware());
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(LoggerMiddleware::class);
    // $app->add(AccountMiddleware::class);

    //!NOTE: the error handling middleware MUST be added last.
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();
    $errorHandler = new CustomErrorHandler($callableResolver, $responseFactory);
    $errorMiddleware->setDefaultErrorHandler($errorHandler);
    $errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);
};
