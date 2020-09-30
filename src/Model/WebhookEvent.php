<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event',
        'webhook_endpoint_id',
    ];

    public function endpoint()
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }
}
