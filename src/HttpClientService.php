<?php

namespace Classid\SsoDriver;


class HttpClientService implements \Classid\SsoDriver\Interfaces\HttpClient
{
    public array $httpRequestHeaders;
    public string $baseUrl;

    public function __construct()
    {
        $this->setDefaultHttpRequestHeader()
            ->setBaseUrl();
    }

    /**
     * @return HttpClientService
     */
    public function setBaseUrl(): HttpClientService
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
     * @return HttpClientService
     */
    public function setDefaultHttpRequestHeader(): HttpClientService
    {
        $this->httpRequestHeaders = [
            "Accept" => "application/json"
        ];

        return $this;
    }


    /**
     * @param array $httpRequestHeaders
     * @return HttpClientService
     */
    public function setHttpRequestHeaders(array $httpRequestHeaders): HttpClientService
    {
        $this->httpRequestHeaders = $httpRequestHeaders;
        return $this;
    }


    /**
     * @param string $key
     * @param string|array $value
     * @return HttpClientService
     */
    public function addHttpRequestHeader(string $key, string|array $value): HttpClientService
    {
        $this->httpRequestHeaders[$key] = $value;
        return $this;
    }


    /**
     * @param array $httpRequestHeaders
     * @return HttpClientService
     */
    public function addHttpRequestHeaders(array $httpRequestHeaders): HttpClientService
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
