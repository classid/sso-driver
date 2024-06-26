<?php

namespace Classid\SsoDriver\Services;


use Illuminate\Support\Facades\Http;

class HttpClientConfiguration implements \Classid\SsoDriver\Interfaces\HttpClientConfigurationInterface
{
    public array $httpRequestHeaders;
    public string $baseUrl;

    public function __construct()
    {
        $this->setDefaultHttpRequestHeader()
            ->setBaseUrl();
    }

    /**
     * @return HttpClientConfiguration
     */
    public function setBaseUrl(): HttpClientConfiguration
    {
        $this->baseUrl = rtrim(config("mumtaz_sso_driver.host"), "/");
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }


    /**
     * @return HttpClientConfiguration
     */
    public function setDefaultHttpRequestHeader(): HttpClientConfiguration
    {
        $this->httpRequestHeaders = [
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ];

        return $this;
    }


    /**
     * @param array $httpRequestHeaders
     * @return HttpClientConfiguration
     */
    public function setHttpRequestHeaders(array $httpRequestHeaders): HttpClientConfiguration
    {
        $this->httpRequestHeaders = $httpRequestHeaders;
        return $this;
    }


    /**
     * @param string $key
     * @param string|array $value
     * @return HttpClientConfiguration
     */
    public function addHttpRequestHeader(string $key, string|array $value): HttpClientConfiguration
    {
        $this->httpRequestHeaders[$key] = $value;
        return $this;
    }


    /**
     * @param array $httpRequestHeaders
     * @return HttpClientConfiguration
     */
    public function addHttpRequestHeaders(array $httpRequestHeaders): HttpClientConfiguration
    {
        $this->httpRequestHeaders = array_merge($this->httpRequestHeaders, $httpRequestHeaders);
        return $this;
    }


    /**
     * @return array
     */
    public function getHttpRequestHeaders(): array
    {
        return $this->httpRequestHeaders;
    }


    /**
     * @param string $key
     * @return array
     */
    public function getHttpRequestHeader(string $key): array
    {
        return $this->httpRequestHeaders[$key];
    }
}
