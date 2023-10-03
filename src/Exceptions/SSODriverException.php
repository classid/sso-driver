<?php

namespace Classid\SsoDriver\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SSODriverException extends Exception
{
    public array|null $data;
    public function __construct(string     $message = "Something went wrong !",
                                ?array $data = [],
                                int        $code = Response::HTTP_UNAUTHORIZED,
                                ?Throwable $previous = null
    )
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
}
