<?php

namespace Classid\SsoDriver\Interfaces;


use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;

interface SSO
{
    /**
     * @return $this
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function setAuthorizationToken(): self;


    /**
     * @param callable $request
     * @param callable|null $customErrorHandler
     * @return object|null
     * @throws InvalidRetryGenerateException|UnknownErrorHandlerException|InvalidClientCredentials|SSODriverException
     */
    public function getResponse(callable $request, callable $customErrorHandler = null): ?object;
}
