<?php

namespace CustomD\WebhookRegistry\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CustomD\WebhookRegistry\Models\Contracts\WebhookEndpointContract;
use CustomD\WebhookRegistry\Models\Traits\GeneratesSecret;
use CustomD\WebhookRegistry\Models\Traits\HasWebhookEvent;

class WebhookEndpoint extends Model implements WebhookEndpointContract
{
    use SoftDeletes;
    use HasWebhookEvent;
    use GeneratesSecret;

    protected $fillable = [
        'description',
        'base_url',
        'verify_ssl',
    ];
}
