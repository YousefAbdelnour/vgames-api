<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpUnauthorizedException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var string
     */
    protected $message = 'Unauthorized Access';

    protected string $title = '401 Unauthorized';
    protected string $description = "You do not have the necessary permissions to perform this task.";
}
