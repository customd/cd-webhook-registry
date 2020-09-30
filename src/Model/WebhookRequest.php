<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;


class WebhookRequest extends Model
{
    protected $fillable = [
        'uuid',
        'httpVerb',
        'webhookUrl',
        'status',
        'payload',
        'meta',
        'responseBody',
        'attempt',
    ];
}
