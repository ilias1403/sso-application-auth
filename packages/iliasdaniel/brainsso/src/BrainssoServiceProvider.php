<?php

namespace Iliasdaniel\Brainsso;

use Illuminate\Support\ServiceProvider;

class BrainssoServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'iliasdaniel');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'iliasdaniel');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/brainsso.php', 'brainsso');

        // Register the service the package provides.
        $this->app->singleton('brainsso', function ($app) {
            return new Brainsso;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['brainsso'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/brainsso.php' => config_path('brainsso.php'),
        ], 'brainsso.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/iliasdaniel'),
        ], 'brainsso.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/iliasdaniel'),
        ], 'brainsso.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/iliasdaniel'),
        ], 'brainsso.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
