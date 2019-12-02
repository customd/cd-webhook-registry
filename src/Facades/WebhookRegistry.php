<?php

namespace CustomD\WebhookRegistry\Facades;

use Illuminate\Support\Facades\Facade;

class WebhookRegistry extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'webhook-registry';
    }
}
