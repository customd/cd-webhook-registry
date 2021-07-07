<?php

namespace CustomD\WebhookRegistry\Events\Traits;

use Illuminate\Contracts\Support\Arrayable;
trait DeliversWebhooks
{
    /**
     * Get the webhook payload
     */
    public function getWebhookPayload(): array
    {
        $user = request()->user();

        return [
            'body' => [
                'event'        => $this->getWebhookEventName(),
                'context'      => $this->getWebhookContext(),
                'triggered_by' => $user ? $user->toArray() : null,
            ]
        ];
    }

    /**
     * The value that will be returned in the `payload`
     */
    public function getWebhookContext(): array
    {
        if ($this->context instanceof Arrayable) {
            return $this->context->toArray();
        }

        return $this->context ?? [];
    }

    /**
     * Should we allow this event to deliver webhooks?
     */
    public function shouldDeliverWebhook(): bool
    {
        return true;
    }

    /**
     * Gets the event name.
     */
    public function getWebhookEventName(): string
    {
        return $this->webhookEventName ?? static::class;
    }
}
