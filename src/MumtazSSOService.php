<?php

namespace Classid\SsoDriver;

use Classid\SsoDriver\Abstracts\BaseMumtazSSOService;
use Classid\SsoDriver\Enums\ResponseCode;
use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\MumtazSSOServiceInterface;
use Classid\SsoDriver\Traits\SSOServiceErrorHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MumtazSSOService extends BaseMumtazSSOService implements MumtazSSOServiceInterface
{
    use SSOServiceErrorHandler;

    /**
     * @return $this
     * @throws InvalidClientCredentials|UnknownErrorHandlerException
     */
    public function setAuthorizationToken():self
    {
        $this->addHeaders([
            "Authorization" => $this->getClientAccessToken()
        ]);
        return $this;
    }


    /**
     * @param array|null $errorResponse
     * @return bool
     * @throws InvalidRetryGenerateException|UnknownErrorHandlerException|InvalidClientCredentials
     */
    public function isRetryOnInvalidAccessToken(?array $errorResponse): bool
    {
        if ($errorResponse["rc"] === ResponseCode::ERR_AUTHENTICATION->name && $errorResponse['message'] === "Unauthenticated.") {
            if ($this->currentRegenerateAttempt > self::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN) {
                throw new InvalidRetryGenerateException("Access token invalid after " . self::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN . " retry generate attempt");
            }

            $this->addHeaders([
                "Authorization" => $this->getClientAccessToken(true)
            ]);

            return true;
        }

        return false;
    }


    /**
     * @param bool $isRegenerate
     * @return string
     * @throws InvalidClientCredentials|UnknownErrorHandlerException
     */
    public function getClientAccessToken(bool $isRegenerate = false): string
    {
        //client access token is empty, generate new
        if ($isRegenerate || ($clientAccessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY)) === null) {
            //hit into oauth/token
            $response = Http::withHeaders($this->getHeaders())->post($this->getBaseUrl() . "/api/v1/oauth/token", [
                "grant_type" => "client_credentials",
                "client_id" => config("services.mumtaz_sso_client_id"),
                "client_secret" => config("services.mumtaz_sso_client_secret") ,
                "scope" => "*"
            ]);

            if ($response->failed()){
                self::unknownResponseErrorHandler($response->json());//for unknown response
                if ($response->json("rc") === ResponseCode::ERR_AUTHENTICATION->name) {
                    throw new InvalidClientCredentials("Regenerate access token failed. Invalid client credentials !");
                }
            }


            //reduce expired to prevent problem when retrieve token from cache and all progress is still valid,
            //but when hit into server it's already invalid
            $expiresIn = self::reduceValueByPercentage($response->json("expires_in"), self::TTL_PERCENTAGE_REDUCER);
            $token = $response->json("access_token");

            Cache::put(
                self::ACCESS_TOKEN_CACHE_KEY,
                $token,
                $expiresIn
            );
            $clientAccessToken = $token;
        }

        return $clientAccessToken;
    }


    /**
     * @param callable $request
     * @param callable|null $customErrorHandler
     * @return object|null
     * @throws InvalidRetryGenerateException|UnknownErrorHandlerException|InvalidClientCredentials|SSODriverException
     */
    public function getResponse(callable $request, callable $customErrorHandler = null): ?object
    {
        do {
            $this->currentRegenerateAttempt++;
            /** @var Response $response */
            $response = $request($this);

            if ($response->failed()){
                self::unknownResponseErrorHandler($response->json());//for unknown response

                if ($this->isRetryOnInvalidAccessToken($response->json())) {
                    continue;
                }

                $customErrorHandler($this, $response);

                self::mappingErrorHandler($response->json()); //for default response
            }
            break;
        } while ($this->currentRegenerateAttempt <= self::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN);

        return $response->object()->payload->data;
    }
}
