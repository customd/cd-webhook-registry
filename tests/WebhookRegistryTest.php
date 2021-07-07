<?php

namespace CustomD\WebhookRegistry\Tests;

use CustomD\WebhookRegistry\Facades\WebhookRegistry;
use CustomD\WebhookRegistry\ServiceProvider;
use Orchestra\Testbench\TestCase;

class WebhookRegistryTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'webhook-registry' => WebhookRegistry::class,
        ];
    }

    public function testCreateConsumer()
    {
        $consumer_id = WebhookRegistry::registerConsumer('test', 'testing', 'https://test.docksal');

        $call_id = WebhookRegistry::registerCall($consumer_id, 'test', 'test', 'run.test');
    }
}
