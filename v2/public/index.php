<?php

declare(strict_types=1);

// This is the front controller of the Slim application.
$app = require realpath(__DIR__ . '/../app/config/bootstrap.php');
$app->setBasePath('/vgames-api/v2');
$app->run();
