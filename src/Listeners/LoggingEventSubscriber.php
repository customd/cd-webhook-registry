<?php

namespace CustomD\WebhookRegistry\Listeners;

use CustomD\WebhookRegistry\Models\WebhookRequest;
use Spatie\WebhookServer\Events\WebhookCallEvent;
use Spatie\WebhookServer\Events\WebhookCallFailedEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;

class LoggingEventSubscriber
{

    public function recordWebhookSuccess(WebhookCallSucceededEvent $event)
    {
        WebhookRequest::create(
            $this->makeLogEntryFromEvent($event, 'success')
        );
    }

    public function recordWebhookFailed(WebhookCallFailedEvent $event)
    {
        WebhookRequest::create(
            $this->makeLogEntryFromEvent($event, 'failed')
        );
    }

    public function recordWebhookFailedFinal(FinalWebhookCallFailedEvent $event)
    {
        WebhookRequest::create(
            $this->makeLogEntryFromEvent($event, 'failed')
        );
    }

    public function makeLogEntryFromEvent(WebhookCallEvent $event, string $status)
    {
        return [
            'uuid' => $event->uuid,
            'httpVerb' => $event->httpVerb,
            'webhookUrl' => $event->webhookUrl,
            'meta' => $event->meta ? json_encode($event->meta) : null,
            'payload' => json_encode($event->payload),
            'responseBody' => $event->response ? $event->response->getBody() : null,
            'attempt' => $event->attempt,
            'status' => $status,
        ];
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $className = self::class;

        $events->listen(
            WebhookCallSucceededEvent::class,
            "$className@recordWebhookSuccess"
        );
        $events->listen(
            WebhookCallFailedEvent::class,
            "$className@recordWebhookFailed"
        );
        $events->listen(
            FinalWebhookCallFailedEvent::class,
            "$className@recordWebhookFailedFinal"
        );
    }
}
