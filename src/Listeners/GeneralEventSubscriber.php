<?php

namespace CustomD\WebhookRegistry\Listeners;

use CustomD\WebhookRegistry\Facades\WebhookRegistry;

class GeneralEventSubscriber
{

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen('App\Events*', function ($eventName, array $payloads) {
            foreach($payloads as $payload){
                if(WebhookRegistry::has($eventName)){
                    WebhookRegistry::trigger($eventName, $payload->getWebhookPayload());
                }
            }
        });
    }
}
