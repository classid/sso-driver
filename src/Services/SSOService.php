<?php

namespace Classid\SsoDriver\Services;

use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\HttpClientConfigurationInterface;
use Classid\SsoDriver\Interfaces\OauthToken;
use Classid\SsoDriver\Interfaces\SSO;
use Classid\SsoDriver\Traits\SSOServiceErrorHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;


class SSOService implements SSO
{
    use SSOServiceErrorHandler;

    public function __construct(
        public HttpClientConfigurationInterface $httpClientConfiguration,
        public OauthToken                       $oauthToken
    )
    {
    }


    /**
     * @return $this
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function setAuthorizationToken(): self
    {
        $this->httpClientConfiguration->addHttpRequestHeader("Authorization", $this->oauthToken->getClientAccessToken());
        return $this;
    }


    /**
     * @param callable $request
     * @param callable|null $customErrorHandler
     * @return array|null
     * @throws InvalidClientCredentials
     * @throws InvalidRetryGenerateException
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function getResponse(callable $request, callable $customErrorHandler = null): array|null
    {
        do {
            $this->oauthToken->retryRequestOauthTokenAttempt++;
            Log::info($this->oauthToken->retryRequestOauthTokenAttempt);
            /** @var Response $response */
            $response = $request($this);

            if ($response->failed()) {
                #for unknown response
                self::unknownResponseErrorHandler($response->json());

                if ($this->oauthToken->isRetryOnInvalidAccessToken($response->json())) {
                    continue;
                }


                if ($customErrorHandler) {
                    $customErrorHandler($this, $response);
                }

                #for default response
                self::mappingErrorHandler($response->json());
            }
            break;
        } while ($this->oauthToken->retryRequestOauthTokenAttempt <= OauthTokenService::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN);

        return $response->json();
    }
}
