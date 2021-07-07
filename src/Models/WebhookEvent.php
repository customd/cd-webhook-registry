<?php

namespace CustomD\WebhookRegistry\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use CustomD\WebhookRegistry\Models\Contracts\WebhookEventContract;
use CustomD\WebhookRegistry\Models\Traits\HasWebhookEndpoint;

class WebhookEvent extends Model implements WebhookEventContract
{
    use SoftDeletes;
    use HasWebhookEndpoint;

    protected $fillable = [
        'event',
        'webhook_endpoint_id',
    ];

    public function scopeWhereDispatchable(Builder $builder): void
    {
        // Do nothing.
    }
}
