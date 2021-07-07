<?php

namespace CustomD\WebhookRegistry\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \CustomD\WebhookRegistry\Listeners\GeneralEventSubscriber::class,
        \CustomD\WebhookRegistry\Listeners\LoggingEventSubscriber::class,
    ];
}
