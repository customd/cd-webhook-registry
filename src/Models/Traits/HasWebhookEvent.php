<?php

namespace CustomD\WebhookRegistry\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use CustomD\WebhookRegistry\Models\WebhookEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use CustomD\WebhookRegistry\Models\Contracts\WebhookEventContract;

trait HasWebhookEvent
{
    public static function bootHasWebhookEndpoints(): void
    {
        static::creating(
            function ($endpoint) {
                $endpoint->secret = bin2hex(random_bytes(8));
            }
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(WebhookEvent::class);
    }

    public function getWebhookPayload(WebhookEventContract $event, array $payload): array
    {
        return $payload;
    }
}
