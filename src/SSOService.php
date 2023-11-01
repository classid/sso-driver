<?php

namespace Classid\SsoDriver;

use Classid\SsoDriver\Exceptions\InvalidClientCredentials;
use Classid\SsoDriver\Exceptions\InvalidRetryGenerateException;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;
use Classid\SsoDriver\Interfaces\HttpClient;
use Classid\SsoDriver\Interfaces\OauthToken;
use Classid\SsoDriver\Traits\SSOServiceErrorHandler;
use Illuminate\Http\Client\Response;


class SSOService implements Interfaces\SSO
{
    use SSOServiceErrorHandler;

    public function __construct(
        public HttpClient $httpClient,
        public OauthToken $oauthToken)
    {
    }

    public function i(){
        $this->oauthToken->retryRequestOauthTokenAttempt++;
    }


    /**
     * @return $this
     * @throws InvalidClientCredentials
     * @throws SSODriverException
     * @throws UnknownErrorHandlerException
     */
    public function setAuthorizationToken(): self
    {
        $this->httpClient->addHttpRequestHeader("Authorization", $this->oauthToken->getClientAccessToken());
        return $this;
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
            $this->oauthToken->retryRequestOauthTokenAttempt++;
            /** @var Response $response */
            $response = $request($this);

            if ($response->failed()) {
                self::unknownResponseErrorHandler($response->json());//for unknown response

                if ($this->oauthToken->isRetryOnInvalidAccessToken($response->json())) {
                    continue;
                }


                if ($customErrorHandler) {
                    $customErrorHandler($this, $response);
                }

                self::mappingErrorHandler($response->json()); //for default response
            }
            break;
        } while ($this->oauthToken->retryRequestOauthTokenAttempt <= OauthTokenService::RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN);

        return $response->object()->payload->data;
    }
}
