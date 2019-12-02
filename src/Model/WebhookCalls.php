<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;

class WebhookCalls extends Model
{
    protected $fillable = [
        'name',
        'url_path',
        'event',
        'webhook_consumer_id',
    ];

    public function consumer()
    {
        return $this->BelongsTo(WebhookConsumers::class);
    }

    public function getUrlAttribute()
    {
        $url = rtrim($this->consumer->base_path, '/');
        $path = ltrim($this->url_path, '/');

        return $url.'/'.$path;
    }
}
