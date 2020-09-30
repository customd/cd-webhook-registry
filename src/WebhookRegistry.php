<?php

namespace CustomD\WebhookRegistry;

use CustomD\WebhookRegistry\Model\WebhookEvent;
use CustomD\WebhookRegistry\Model\WebhookEndpoint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Spatie\WebhookServer\WebhookCall;

class WebhookRegistry
{
    /**
     * Method to register a new endpoint.
     *
     * @param string $baseUrl = base url of service
     * @param string $description = description of the endpoint service
     * @param bool $verifySSL = whether or not to verify ssl
     *
     * @return WebhookEndpoint  -record id
     */
    public function registerEndpoint(string $baseUrl, string $description, bool $verifySSL = true): WebhookEndpoint
    {
        $record = WebhookEndpoint::create([
            'description' => $description,
            'base_url' => $baseUrl,
            'verify_ssl' => (int) $verifySSL,
        ]);

        return $record;
    }

    /**
     * register a event to a service.
     *
     * @param int $webhookId
     * @param string $description
     * @param string $path
     * @param string $event
     *
     * @return int
     */
    public function registerEvent(int $webhookId, string $event): WebhookEvent
    {
        $record = WebhookEvent::create([
            'event' => $event,
            'webhook_id' => $webhookId,
        ]);

        return $record;
    }

    /**
     * Delete a event.
     *
     * @param int $eventId
     *
     * @throws ModelNotFoundException
     */
    public function deleteEvent(int $eventId): void
    {
        WebhookEndpoint::findOrFail($eventId)->delete();
    }

    /**
     * Restored a deleted event.
     *
     * @param int $eventId
     *
     * @throws ModelNotFoundException
     */
    public function restoreEvent(int $eventId): void
    {
        WebhookEndpoint::withTrashed()->findOrFail($eventId)->restore();
    }

    /**
     * Get events from the database.
     *
     * @param string|null $event
     * @param bool $includeDeleted
     *
     * @return Collection
     */
    public function getEvents(?string $event = null, bool $includeDeleted = false): Collection
    {
        $model = WebhookEvent::with('endpoint');

        if ($event) {
            $model->where('event', $event);
        }

        if ($includeDeleted) {
            $model->withTrashed();
        }

        return $model->get();
    }

    /**
     * Trigger webhooks for a specific event.
     *
     * @param string $event
     * @param array $payload
     */
    public function trigger(string $event, array $payload = []): void
    {
        $events = $this->getEvents($event);

        if ($events->isEmpty()) {
            return;
        }

        foreach ($events as $event) {
            $this->dispatchWebhook($event->endpoint, $payload);
        }
    }

    protected function dispatchWebhook(WebhookEndpoint $endpoint, array $payload): void
    {
        $hook = WebhookCall::create()
            ->url($endpoint->base_url)
            ->payload($payload['body'] ?? [])
            ->useSecret($endpoint->secret)
            ->timeoutInSeconds(30)
            ->maximumTries(1);

        if (! $endpoint->verify_ssl) {
            $hook->doNotVerifySsl();
        }

        if (Arr::has($payload, 'tags')) {
            $hook->withTags($payload['tags']);
        }

        if (Arr::has($payload, 'meta')) {
            $hook->meta($payload['meta']);
        }

        // No need to queue. The developer can queue their events if they wish.
        $hook->dispatchNow();
    }

    /**
     * Does the event have any available hooks.
     *
     * @param string $event
     * @param bool $includeDeleted
     *
     * @return bool
     */
    public function has(string $event, $includeDeleted = false): bool
    {
        $status = WebhookEvent::where('event', $event);

        if ($includeDeleted) {
            $status->withTrashed();
        }

        return $status->count() > 0 ? true : false;
    }
}
