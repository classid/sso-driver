<?php

namespace Classid\SsoDriver\Interfaces;



interface HttpClient
{
    /**
     * @return string
     */
    public function getBaseUrl():string;

    /**
     * @return HttpClient
     */
    public function setBaseUrl():HttpClient;

    /**
     * @return HttpClient
     */
    public function setDefaultHttpRequestHeader(): HttpClient;

    /**
     * @param array $httpRequestHeaders
     * @return HttpClient
     */
    public function setHttpRequestHeaders(array $httpRequestHeaders): HttpClient;

    /**
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function addHttpRequestHeader(string $key, string|array $value): HttpClient;

    /**
     * @param array $httpRequestHeaders
     * @return HttpClient
     */
    public function addHttpRequestHeaders(array $httpRequestHeaders): HttpClient;

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
