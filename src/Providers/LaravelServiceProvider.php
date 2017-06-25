<?php namespace Kevindierkx\Api\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }

    /**
     * Setup the package config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $path = realpath(__DIR__."/../../config/api.php");

        $this->publishes([$path => config_path("api.php")]);

        $this->mergeConfigFrom($path, 'api');
    }
}
