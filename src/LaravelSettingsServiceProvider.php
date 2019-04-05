<?php

namespace OmarAliGit\Settings;

use Illuminate\Support\ServiceProvider;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * loading the config
         */
        $this->publishes([
            __DIR__.'/../config/laravel-settings.php' => config_path('laravel-settings.php'),
        ]);
        /**
         * loading the migrations
         */
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}
