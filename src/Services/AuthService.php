<?php

namespace Classid\SsoDriver\Services;

use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\SSO;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Http;

class AuthService
{
    protected SSO $sso;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->sso = app()->make(SSO::class);
    }

    /**
     * @param array $credentials
     * @param string $endpoint
     * @return mixed
     * @throws InvalidClientCredentials
     * @throws InvalidRetryGenerateException
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function authenticate(array $credentials, string $endpoint = "/api/v1/auth"): mixed
    {
        return $this->sso->setAuthorizationToken()
            ->getResponse(function (SSOService $service) use ($credentials, $endpoint) {
                return Http::withHeaders($service->httpClientConfiguration->getHttpRequestHeaders())
                    ->post($service->httpClientConfiguration->getBaseUrl() . $endpoint, [
                        "username" => $credentials["username"],
                        "password" => $credentials["password"],
                        "institution_id" => $credentials["institution_id"],
                    ]);
            });
    }
}
