<?php

namespace Classid\SsoDriver\Exceptions;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;


class InvalidRetryGenerateException extends Exception
{
    public function __construct(string     $message = "Access token invalid after retry generate",
                                int        $code = Response::HTTP_FORBIDDEN,
                                ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
