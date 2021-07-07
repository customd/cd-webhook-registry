<?php

namespace CustomD\WebhookRegistry\Contracts;

interface ShouldDeliverWebhooks
{

     /**
      * Get the payload for this webhook event
      */
    public function getWebhookPayload(): array;

    /**
     * Get the payload context for this webhook event
     */
    public function getWebhookContext(): array;

    /**
     * Should we allow this event to deliver webhooks?
     */
    public function shouldDeliverWebhook(): bool;

    /**
     * Gets the event name.
     */
    public function getWebhookEventName(): string;
}
