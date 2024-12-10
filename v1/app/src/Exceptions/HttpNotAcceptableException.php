<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpNotAcceptableException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 415;

    /**
     * @var string
     */
    protected $message = 'Unacceptable media type.';

    protected string $title = '415 Unacceptable media type';
    protected string $description = 'The content type that was requested is not available. Please use Application/json as accepted media type.';
}
