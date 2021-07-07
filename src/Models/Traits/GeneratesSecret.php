<?php

namespace CustomD\WebhookRegistry\Models\Traits;

trait GeneratesSecret
{
    public static function bootGeneratesSecret(): void
    {
        static::creating(
            function ($model) {
                $model->secret = bin2hex(random_bytes(8));
            }
        );
    }
}
