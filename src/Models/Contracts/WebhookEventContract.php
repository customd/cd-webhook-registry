<?php

namespace CustomD\WebhookRegistry\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

interface WebhookEventContract
{

     /**
      * Get the endpoint relationship
      */
    public function endpoint(): BelongsTo;

    /**
     * A scope which determines whether this webhook endpoint will actually send the webhook.
     */
    public function scopeWhereDispatchable(Builder $builder): void;
}
