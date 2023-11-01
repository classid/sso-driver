<?php

namespace Classid\SsoDriver\Interfaces;


use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;

interface OauthToken
{
    /**
     * @param array|null $errorResponse
     * @return bool
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException|InvalidRetryGenerateException
     */
    public function isRetryOnInvalidAccessToken(?array $errorResponse): bool;


    /**
     * @param bool $isRegenerate
     * @return string
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function getClientAccessToken(bool $isRegenerate = false): string;
}
