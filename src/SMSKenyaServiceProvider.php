<?php

namespace CraftedSystems\SMSKenya;

use Illuminate\Support\ServiceProvider;

class SMSKenyaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/Config/smskenya.php' => config_path('smskenya.php'),
        ], 'sms_kenya_config');

        $this->app->singleton(SMSKenya::class, function () {
            return new SMSKenya(config('smskenya'));
        });

        $this->app->alias(SMSKenya::class, 'sms-kenya');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/smskenya.php', 'sms-kenya'
        );
    }
}
