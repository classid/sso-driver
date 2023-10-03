<?php

namespace Classid\SsoDriver\Abstracts;

abstract class BaseMumtazSSOService
{
    public array $headers;
    protected const TTL_PERCENTAGE_REDUCER = 0.2;
    public const ACCESS_TOKEN_CACHE_KEY = "mumtaz_sso_client_access_token";
    public string $baseUrl;
    public int $currentRegenerateAttempt;
    public const RETRY_ATTEMPT_ON_INVALID_ACCESS_TOKEN = 1;


    public function __construct()
    {
        $this->headers = [
            "Accept" => "application/json"
        ];
        $this->baseUrl = config("services.mumtaz_sso_client_host");
        $this->currentRegenerateAttempt = 0;
    }


    /**
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers):BaseMumtazSSOService
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }


    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * description : use to reduce value by percentage
     * (50, 0.2) => 50 - (50 * 0.2)
     *           => 50 - 10
     *           => 40
     * @param int $value
     * @param float $percentage
     * @return float
     */
    public static function reduceValueByPercentage(int $value, float $percentage): float
    {
        return $value - ($value * $percentage);
    }
}
