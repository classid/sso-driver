<?php

namespace Classid\SsoDriver\Services;

use Classid\SsoDriver\Enums\ResponseCode;
use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\HttpClientConfigurationInterface;
use Classid\SsoDriver\Interfaces\OauthToken;
use Classid\SsoDriver\Traits\SSOServiceErrorHandler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OauthTokenService implements OauthToken
{
    use SSOServiceErrorHandler;
    protected const ACCESS_TOKEN_CACHE_KEY = "mumtaz_sso_client_access_token";
    protected const TTL_PERCENTAGE_REDUCER = 0.2;
    public const RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN = 1;
    public int $retryRequestOauthTokenAttempt;

    public function __construct(public HttpClientConfigurationInterface $httpClientConfiguration)
    {
        $this->retryRequestOauthTokenAttempt = 0;
    }

    /**
     * @description : this method will return client access token when cache null,
     * or we can force to generate using $isRegenerate
     * @param bool $isRegenerate
     * @return string
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function getClientAccessToken(bool $isRegenerate = false): string
    {
        #client access token is empty, generate new
        if ($isRegenerate || ($clientAccessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY)) === null) {
            #hit into oauth/token
            $response = Http::withHeaders($this->httpClientConfiguration->getHttpRequestHeaders())
                ->post($this->httpClientConfiguration->getBaseUrl() . "/api/v1/oauth/token", [
                    "grant_type" => "client_credentials",
                    "client_id" => config("mumtaz_sso_driver.client_id"),
                    "client_secret" => config("mumtaz_sso_driver.client_secret"),
                    "scope" => "*"
                ]);


            if ($response->failed()) {
                self::unknownResponseErrorHandler($response->json());//for unknown response
                if ($response->json("rc") === ResponseCode::ERR_AUTHENTICATION->name) {
                    throw new InvalidClientCredentials("Regenerate access token failed. Invalid client credentials !");
                }
                self::mappingErrorHandler($response->json());
            }


            #reduce expired to prevent problem when retrieve token from cache and all progress is still valid,
            #but when hit into server it's already invalid
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
     * @description : this method used
     * @param array|null $errorResponse
     * @return bool
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException|InvalidRetryGenerateException
     */
    public function isRetryOnInvalidAccessToken(?array $errorResponse): bool
    {
        if ($errorResponse["rc"] === ResponseCode::ERR_AUTHENTICATION->name && $errorResponse['message'] === "Unauthenticated.") {
            if ($this->retryRequestOauthTokenAttempt > self::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN) {
                throw new InvalidRetryGenerateException("Access token invalid after " . self::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN . " retry generate attempt");
            }

            $this->httpClientConfiguration->addHttpRequestHeader("Authorization", $this->getClientAccessToken(true));

            return true;
        }

        return false;
    }


    /**
     * @param int $value
     * @param float $percentage
     * @return float
     */
    private static function reduceValueByPercentage(int $value, float $percentage): float
    {
        return $value - ($value * $percentage);
    }
}
