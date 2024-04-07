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
