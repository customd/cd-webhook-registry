<?php

namespace CustomD\WebhookRegistry\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface WebhookEndpointContract
{
     /**
      * Get the events relationship
      */
    public function events(): HasMany;

    /**
     * Get the webhook payload, customised for this specific endpoint.
     *
     * @return array
     */
    public function getWebhookPayload(WebhookEventContract $event, array $payload): array;

    /**
     * Should we allow this event to deliver webhooks?
     *
     * @return bool
     */
    public function shouldDeliverWebhook(WebhookEventContract $event, array $payload): bool;
}
