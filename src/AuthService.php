<?php

namespace Classid\SsoDriver;

use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public MumtazSSOService $mumtazSSOService;
    public function __construct()
    {
        $this->mumtazSSOService = new MumtazSSOService();
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
        return $this->mumtazSSOService->setAuthorizationToken()
            ->getResponse(function (MumtazSSOService $service) use ($credentials) {
                return Http::withHeaders($service->getHeaders())->post($service->getBaseUrl() . "/api/v1/auth", [
                    "username" => $credentials["username"],
                    "password" => $credentials["password"],
                    "institution_id" => $credentials["institution_id"], //todo : hardcode for integration testing
                ]);
            });
    }
}
