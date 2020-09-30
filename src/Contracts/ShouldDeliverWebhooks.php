<?php

namespace CustomD\WebhookRegistry\Contracts;

interface ShouldDeliverWebhooks {

     /**
      * Get the webhook payload
      *
      * @return array
      */
     public function getWebhookPayload(): array;
}
