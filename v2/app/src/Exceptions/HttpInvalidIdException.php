<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidIdException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid ID provided.';

    protected string $title = '400 Bad Request';
    protected string $description = "ID's must be composed of digits or letters only, not both.";
}
