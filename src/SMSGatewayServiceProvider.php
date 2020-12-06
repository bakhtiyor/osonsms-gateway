<?php

namespace OsonSMS\SMSGateway;

use Illuminate\Support\ServiceProvider;

class SMSGatewayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'smsgateway');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'smsgateway');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('smsgateway.php'),
            ], 'config');

            // Export the migration
            if (! class_exists('CreateOsonsmsLogTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_osonsms_log_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_osonsms_log_table.php'),
                    // you can add any number of migrations here
                ], 'migrations');
            }
            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/smsgateway'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/smsgateway'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/smsgateway'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'smsgateway');

        // Register the main class to use with the facade
        $this->app->singleton('smsgateway', function () {
            return new SMSGateway;
        });
    }
}
