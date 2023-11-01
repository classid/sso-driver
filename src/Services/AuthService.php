<?php

namespace Classid\SsoDriver\Services;

use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\SSO;
use Classid\SsoDriver\SSOService;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function __construct(public SSOService $sso)
    {
    }

    /**
     * @param array $credentials
     * @return mixed
     * @throws InvalidClientCredentials
     * @throws InvalidRetryGenerateException
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function authenticate(array $credentials): mixed
    {
        return $this->sso->setAuthorizationToken()
            ->getResponse(function (SSOService $service) use ($credentials) {
                return Http::withHeaders($service->httpClient->getHttpRequestHeaders())
                    ->post($service->httpClient->getBaseUrl() . "/api/v1/auth", [
                        "username" => $credentials["username"],
                        "password" => $credentials["password"],
                        "institution_id" => $credentials["institution_id"],
                    ]);
            });
    }
}
