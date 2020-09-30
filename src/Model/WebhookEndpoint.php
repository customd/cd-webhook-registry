<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookEndpoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
        'base_url',
        'verify_ssl',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($endpoint) {
            $endpoint->secret = bin2hex(random_bytes(8));
        });
    }

    public function events()
    {
        return $this->hasMany(WebhookEvent::class);
    }
}
