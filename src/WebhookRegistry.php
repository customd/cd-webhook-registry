<?php

namespace CustomD\WebhookRegistry;

use CustomD\WebhookRegistry\Model\WebhookCalls;
use CustomD\WebhookRegistry\Model\WebhookConsumers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Spatie\WebhookServer\WebhookCall;

class WebhookRegistry
{
    /**
     * Method to register a new consumer.
     *
     * @param string $name = name of the consumer service
     * @param string $token = token for verification of signature
     * @param string $baseUrl = base url of service
     * @param bool $verifySSL = whether or not to verify ssl
     *
     * @return int  -record id
     */
    public function registerConsumer(string $name, string $token, string $baseUrl, bool $verifySSL = true): int
    {
        $record = WebhookConsumers::create([
            'name' => $name,
            'token' => $token,
            'base_url' => $baseUrl,
            'verify_ssl' => (int) $verifySSL,
        ]);

        return $record->id;
    }

    /**
     * register a call to a service.
     *
     * @param int $consumerId
     * @param string $name
     * @param string $path
     * @param string $event
     *
     * @return int
     */
    public function registerCall(int $consumerId, string $name, string $path, string $event): int
    {
        $record = WebhookCalls::create([
            'name' => $name,
            'url_path' => $path,
            'event' => $event,
            'webhook_consumer_id' => $consumerId,
        ]);

        return $record->id;
    }

    /**
     * Delete a call.
     *
     * @param int $callId
     *
     * @throws ModelNotFoundException
     */
    public function deleteCall(int $callId): void
    {
        WebhookConsumers::findOrFail($callId)->delete();
    }

    /**
     * Restored a deleted call.
     *
     * @param int $callId
     *
     * @throws ModelNotFoundException
     */
    public function restoreCall(int $callId): void
    {
        WebhookConsumers::withTrashed()->findOrFail($callId)->restore();
    }

    /**
     * Get calls from the database.
     *
     * @param string|null $event
     * @param bool $includeDeleted
     *
     * @return Collection
     */
    public function getCalls(?string $event = null, bool $includeDeleted = false): Collection
    {
        $model = WebhookCalls::with('consumers');

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
        $calls = $this->getCalls($event);

        if ($calls->isEmpty()) {
            return;
        }

        foreach ($calls as $call) {
            $this->dispatchWebookCall($call, $payload);
        }
    }

    protected function dispatchWebookCall(WebhookCalls $call, array $payload): void
    {
        $hook = WebhookCall::create()
            ->url($call->url)
            ->payload($payload['body'] ?? [])
            ->useSecret($call->consumer->token);

        if (! $call->consumer->verify_ssl) {
            $hook->doNotVerifySsl();
        }

        if (Arr::has($payload, 'tags')) {
            $hook->withTags($payload['tags']);
        }

        if (Arr::has($payload, 'meta')) {
            $hook->meta($payload['meta']);
        }

        $hook->dispatch();
    }

    /**
     * Does the event have any available calls.
     *
     * @param string $event
     * @param bool $includeDeleted
     *
     * @return bool
     */
    public function has(string $event, $includeDeleted = false): bool
    {
        $status = WebhookCalls::where('event', $event);

        if ($includeDeleted) {
            $status->withTrashed();
        }

        return $status->count() > 0 ? true : false;
    }
}
