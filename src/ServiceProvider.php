<?php

namespace CustomD\WebhookRegistry;

use CustomD\WebhookRegistry\Providers\EventServiceProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected const CONFIG_PATH = __DIR__.'/../config/webhook-registry.php';

    protected const MIGRATIONS_PATH = __DIR__.'/../database/migrations/';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('webhook-registry.php'),
        ], 'config');
        $this->loadMigrationsFrom(self::MIGRATIONS_PATH);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'webhook-registry'
        );

        $this->app->bind('webhook-registry', static function () {
            return new WebhookRegistry();
        });

        $this->app->register(EventServiceProvider::class);
    }
}
