<?php

namespace Iliasdragneel\BrainSsoAuth;

use Illuminate\Support\ServiceProvider;

class BrainSsoAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/brain_sso.php' => config_path('brain_sso.php'),
        ], 'config');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views if needed
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'brain_sso');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/brain_sso.php', 'brain_sso');
    }
}
