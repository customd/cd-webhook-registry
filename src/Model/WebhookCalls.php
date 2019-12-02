<?php

namespace CustomD\WebhookRegistry\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * CustomD\WebhookRegistry\Model\WebhookCalls
 *
 * @property int $id
 * @property string $name
 * @property string $url_path
 * @property string $event
 * @property int $webhook_consumer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read CustomD\WebhookRegistry\Model\WebhookConsumers $consumer
 * @property-read string $url
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls whereRsaKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomD\WebhookRegistry\Model\WebhookCalls whereUpdatedAt($value)
 */
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

    public function getUrlAttribute(): string
    {
        $url = rtrim($this->consumer->base_url, '/');
        $path = ltrim($this->url_path, '/');

        return $url.'/'.$path;
    }
}
