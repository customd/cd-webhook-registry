<?php

return [
    'models' => [
        'endpoint' => \CustomD\WebhookRegistry\Models\WebhookEndpoint::class,
        'event'    => \CustomD\WebhookRegistry\Models\WebhookEvent::class,
        'request'  => \CustomD\WebhookRegistry\Models\WebhookRequest::class,
    ],
];
