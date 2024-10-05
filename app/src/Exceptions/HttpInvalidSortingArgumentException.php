<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidSortingArgumentException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid Sorting argument.';

    protected string $title = '400 Bad Request';
    protected string $description = 'Sorting arguments must be either asc or desc and a valid field from resource that can be a valid sorting argument.';
}
