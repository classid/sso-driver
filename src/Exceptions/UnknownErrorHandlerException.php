<?php

namespace Classid\SsoDriver\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnknownErrorHandlerException extends Exception
{
    public function __construct(string     $message = "Something went wrong on sso auth !",
                                int        $code = Response::HTTP_INTERNAL_SERVER_ERROR,
                                ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
