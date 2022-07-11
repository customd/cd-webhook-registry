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
     * @param array $eventPayload
     * @return array
     */
    public function getWebhookPayload(array $eventPayload): array;
}
