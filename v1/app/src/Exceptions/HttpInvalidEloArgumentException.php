<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidEloArgumentException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid Elo Calculator argument.';

    protected string $title = '400 Bad Request';
    protected string $description = 'Arguments for elo calculation are invalid. Please check your inputs and try again.';
}
