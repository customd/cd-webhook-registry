<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * CustomD\WebhookRegistry\Model\WebhookConsumers
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property string $base_url
 * @property int $verify_ssl
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read CustomD\WebhookRegistry\Model\WebhookCalls $calls
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers whereRsaKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CustomD\WebhookRegistry\Model\WebhookConsumers whereUpdatedAt($value)
 */
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
