<?php

namespace Eyamin\Mediawp;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views','mediawp');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/');
        $this->mergeConfigFrom(__DIR__.'/config/mediawp.php','mediawp');

        $this->publishes([
            __DIR__.'/config/mediawp.php' => config_path('mediawp.php'),
        ]);

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('/'),
        ], 'public');
    }
}
