<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;

class WebhookConsumers extends Model
{
    protected $fillable = [
        'name',
        'token',
        'base_url',
        'verify_ssl',
    ];

    public function calls()
    {
        return $this->hasMany(WebhookCalls::class);
    }
}
