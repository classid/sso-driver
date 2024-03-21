<?php

namespace Classid\SsoDriver;

use Classid\SsoDriver\Interfaces\HttpClientConfigurationInterface;
use Classid\SsoDriver\Interfaces\OauthToken;
use Classid\SsoDriver\Interfaces\SSO;
use Classid\SsoDriver\Services\HttpClientConfiguration;
use Classid\SsoDriver\Services\OauthTokenService;
use Classid\SsoDriver\Services\SSOService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MumtazSSODriverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->publishes([
            __DIR__ . "/Config/mumtaz_sso_driver.php" => config_path("mumtaz_sso_driver.php")
        ]);
        $this->mergeConfigFrom(__DIR__ . "/Config/mumtaz_sso_driver.php", "mumtaz_sso_driver");

        $this->app->singleton(HttpClientConfigurationInterface::class, function () {
            return new HttpClientConfiguration();
        });

        $this->app->singleton(OauthToken::class, function (Application $app) {
            $httpClient = $app->make(HttpClientConfigurationInterface::class);
            return new OauthTokenService($httpClient);
        });

        $this->app->singleton(SSO::class, function (Application $app) {
            $httpClient = $app->make(HttpClientConfigurationInterface::class);
            $oauthTokenService = $app->make(OauthToken::class);
            return new SSOService($httpClient, $oauthTokenService);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
