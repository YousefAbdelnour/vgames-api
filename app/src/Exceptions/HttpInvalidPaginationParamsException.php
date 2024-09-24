<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidPaginationParamsException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid pagination parameter(s).';

    protected string $title = '400 Bad Request';
    protected string $description = 'Pagination parameters must be positive numbers.';
}
