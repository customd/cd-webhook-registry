<?php

namespace CustomD\WebhookRegistry\Models\Traits;

use CustomD\WebhookRegistry\Models\WebhookEndpoint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasWebhookEndpoint
{
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(config('webhook-registry.models.endpoint'), 'webhook_endpoint_id');
    }
}
