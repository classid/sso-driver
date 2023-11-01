<?php

namespace Classid\SsoDriver;

use Illuminate\Support\ServiceProvider;

class MumtazSSODriverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->publishes([
            __DIR__."/Config/mumtaz_sso_driver.php" => config_path("mumtaz_sso_driver.php")
        ]);
        $this->mergeConfigFrom(__DIR__."/Config/mumtaz_sso_driver.php", "mumtaz_sso_driver");
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
