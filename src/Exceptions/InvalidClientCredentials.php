<?php

namespace Classid\SsoDriver\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidClientCredentials extends Exception
{
    public function __construct(string     $message = "Regenerate access token failed. Invalid client credentials !",
                                int        $code = Response::HTTP_UNAUTHORIZED,
                                ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
