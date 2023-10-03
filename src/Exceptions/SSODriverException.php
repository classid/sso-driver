<?php

namespace Classid\SsoDriver\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SSODriverException extends Exception
{
    public array|null $errorResponse;

    public function __construct(string     $message = "Something went wrong !",
                                ?array     $errorResponse = [],
                                int        $code = Response::HTTP_UNAUTHORIZED,
                                ?Throwable $previous = null
    )
    {
        $this->errorResponse = $errorResponse;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorResponse():?array
    {
        return $this->errorResponse;
    }
}
