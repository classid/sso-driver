<?php

namespace Classid\SsoDriver\Interfaces;



interface HttpClientConfigurationInterface
{
    /**
     * @return string
     */
    public function getBaseUrl():string;

    /**
     * @return HttpClientConfigurationInterface
     */
    public function setBaseUrl():HttpClientConfigurationInterface;

    /**
     * @return HttpClientConfigurationInterface
     */
    public function setDefaultHttpRequestHeader(): HttpClientConfigurationInterface;

    /**
     * @param array $httpRequestHeaders
     * @return HttpClientConfigurationInterface
     */
    public function setHttpRequestHeaders(array $httpRequestHeaders): HttpClientConfigurationInterface;

    /**
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function addHttpRequestHeader(string $key, string|array $value): HttpClientConfigurationInterface;

    /**
     * @param array $httpRequestHeaders
     * @return HttpClientConfigurationInterface
     */
    public function addHttpRequestHeaders(array $httpRequestHeaders): HttpClientConfigurationInterface;

    /**
     * @return array
     */
    public function getHttpRequestHeaders(): array;

    /**
     * @param string $key
     * @return array
     */
    public function getHttpRequestHeader(string $key):array;
}
