<?php

namespace CustomD\WebhookRegistry\Models\Traits;

use CustomD\WebhookRegistry\Models\WebhookEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
