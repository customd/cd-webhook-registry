<?php

namespace CustomD\WebhookRegistry;

use CustomD\WebhookRegistry\Models\WebhookEvent;
use CustomD\WebhookRegistry\Models\WebhookEndpoint;
use CustomD\WebhookRegistry\Models\Contracts\WebhookEndpointContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Spatie\WebhookServer\WebhookCall;

class WebhookRegistry
{
    /**
     * Method to register a new endpoint.
     *
     * @param string $baseUrl     = base url of service
     * @param string $description = description of the endpoint service
     * @param bool   $verifySSL   = whether or not to verify ssl
     *
     * @return WebhookEndpoint  -record id
     */
    public function registerEndpoint(string $baseUrl, string $description, bool $verifySSL = true): WebhookEndpoint
    {
        $webhookEndpointModel = resolve(config('webhook-registry.models.endpoint'));

        $record = $webhookEndpointModel::create(
            [
            'description' => $description,
            'base_url' => $baseUrl,
            'verify_ssl' => (int) $verifySSL,
            ]
        );

        return $record;
    }

    /**
     * register a event to a service.
     *
     * @param int    $webhookId
     * @param string $description
     * @param string $path
     * @param string $event
     *
     * @return int
     */
    public function registerEvent(int $webhookId, string $event): WebhookEvent
    {
        $webhookEventModel = resolve(config('webhook-registry.models.event'));

        $record = $webhookEventModel::create(
            [
            'event' => $event,
            'webhook_endpoint_id' => $webhookId,
            ]
        );

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
        $webhookEndpointModel = resolve(config('webhook-registry.models.endpoint'));
        $webhookEndpointModel::findOrFail($eventId)->delete();
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
        $webhookEndpointModel = resolve(config('webhook-registry.models.endpoint'));
        $webhookEndpointModel ::withTrashed()->findOrFail($eventId)->restore();
    }

    /**
     * Get events from the database.
     *
     * @param string|null $event
     * @param bool        $includeDeleted
     *
     * @return Collection
     */
    public function getEvents(?string $event = null, bool $includeDeleted = false): Collection
    {
        $webhookEventModel = resolve(config('webhook-registry.models.event'));
        $model = $webhookEventModel::with('endpoint');

        if ($event) {
            $model->whereEvent($event);
        }

        if ($includeDeleted) {
            $model->withTrashed();
        }

        return $model->get();
    }

    /**
     * Get webhook events that can be dispatched.
     *
     * @param string|null $event
     *
     * @return Collection
     */
    public function getDispatchableEvents(?string $event = null): Collection
    {
        $webhookEventModel = resolve(config('webhook-registry.models.event'));
        $model = $webhookEventModel::with('endpoint');

        if ($event) {
            $model->whereEvent($event);
        }

        return $model->whereDispatchable()->get();
    }

    /**
     * Trigger webhooks for a specific event.
     *
     * @param string $event
     * @param array  $payload
     */
    public function trigger(string $event, array $payload = []): void
    {
        $events = $this->getDispatchableEvents($event);

        if ($events->isEmpty()) {
            return;
        }

        foreach ($events as $event) {
            $this->dispatchWebhook($event->endpoint, $payload);
        }
    }

    protected function dispatchWebhook(WebhookEndpointContract $endpoint, array $payload): void
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
     * @param bool   $includeDeleted
     *
     * @return bool
     */
    public function has(string $event, $includeDeleted = false): bool
    {
        $webhookEventModel = resolve(config('webhook-registry.models.event'));
        $status = $webhookEventModel::where('event', $event);

        if ($includeDeleted) {
            $status->withTrashed();
        }

        return $status->count() > 0 ? true : false;
    }
}
