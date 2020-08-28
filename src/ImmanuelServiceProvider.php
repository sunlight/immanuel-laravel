<?php

namespace Sunlight\Immanuel;

use Illuminate\Support\ServiceProvider;

class ImmanuelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'immanuel');

        $this->app->bind('immanuel', function($app) {
            return new Immanuel();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('immanuel.php'),
            ], 'config');
        }
    }
}