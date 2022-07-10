# Webhook Registry

[![Build Status](https://travis-ci.org/custom-d/webhook-registry.svg?branch=master)](https://travis-ci.org/custom-d/webhook-registry)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/custom-d/webhook-registry/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/custom-d/webhook-registry/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/custom-d/webhook-registry/badge.svg?branch=master)](https://coveralls.io/github/custom-d/webhook-registry?branch=master)

[![Packagist](https://img.shields.io/packagist/v/custom-d/webhook-registry.svg)](https://packagist.org/packages/custom-d/webhook-registry)
[![Packagist](https://poser.pugx.org/custom-d/webhook-registry/d/total.svg)](https://packagist.org/packages/custom-d/webhook-registry)
[![Packagist](https://img.shields.io/packagist/l/custom-d/webhook-registry.svg)](https://packagist.org/packages/custom-d/webhook-registry)


A wrapper for [spatie/laravel-webhook-server](https://github.com/spatie/laravel-webhook-server) that provides a simple & flexible webhook registry, allows you to expose any Laravel Event as a webhook, and provides out-of-the-box logging of webhook results.

## Installation

Install via composer
```bash
composer require custom-d/webhook-registry
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="CustomD\WebhookRegistry\ServiceProvider" --tag="config"
```

## Usage

### 1. Trigger registered webhooks via events

Implement the `CustomD\WebhookRegistry\Contracts\ShouldDeliverWebhooks` contract on any Laravel Event, and you'll be able to register webhooks that will be fired on this event.


This contract defines several methods that need to be defined on your event, which will help provide the payload details.

Method | Purpose
-----|-----
`getWebhookPayload` | Returns the payload to send to the Spatie Webhook call. Useful if you want to completely override the payload.
`getWebhookContext` | Returns the payload `context` field. Useful if you want to set a different property. Defaults to returning the `->context` property from the event. If this property is `Arrayable`, we'll call `->toArray()`. You can overload this method on your event if you want to customise this behaviour.
`getWebhookEventName` | Returns the event name you want to expose in the payload. Useful for endpoints who recieve multiple different types of event to determine how to handle calls. By default, this is the value of the `->webhookEventName` property from the event, otherwise we fall back to the event class name.
`shouldDeliverWebhook` | Whether to deliver webhooks for this event. Defaults to true.

Your payload must define a `body`, but can also define [tags](https://github.com/spatie/laravel-webhook-server#adding-tags) and [meta](https://github.com/spatie/laravel-webhook-server#adding-meta-information) information for passing to `Spatie\WebhookServer\WebhookCall`.

```php
    /**
     * Get the payload for this webhook event
     */
    public function getWebhookPayload(): array
    {
        return [
            'body' => [
                'status' => $this->status,
            ]
        ];
    }
```

### 2. Register webooks

Create webhook endpoints direction using the `CustomD\WebhookRegistry\Models\WebhookEndpoint` model, or use the facade `WebhookRegistry::registerEndpoint`.

```php
$endpoint = WebhookRegistry::registerEndpoint(
  'https://webhook.site/custom/endpoint',
  'My webhook endpoint name'
);
```

By default, we'll verify SSL certificates on outgoing webhook connections. If you want to disable SSL verification, you can pass `false` to the `registerEndpoint` function.

```php
$endpoint = WebhookRegistry::registerEndpoint(
  'https://localhost/webhook-test',
  'My insecure endpoint',
  false
);
```

### 3. Bind events to an endpoint

Associate a webhook event to an endpoint with the `CustomD\WebhookRegistry\Model\WebhookEvent` model, or using the facade `WebhookRegistry::registerEvent`.

```
$event = WebhookRegistry::registerEvent($webhook->id, 'App\Events\MyEvent');
```

## Models

Customise the WebhookEvent model in order to add logic about when events should fire. In `config/webhook-registry.php` you will see the models being used in `models` -> `events`. You can make your own models by implementing the appropriate contract, and updating the config file.

```
WebhookEndpoint extends Model implements CustomD\WebhookRegistry\Models\Contracts\WebhookEndpointContract
WebhookEvent extends Model implements CustomD\WebhookRegistry\Models\Contracts\WebhookEventContract
```

If you need to modify the `WebhookRequest` logging table, note that it doesn't need a contract and can be overridden as needed, but ensure the original fields exist, or define appropriate attribute mappings.

### Custom 'dispatchable' logic

To determine which events are dispatchable in a given execution, you must set a `scopeWhereDispatchable` on the `WebhookEvent` model. This scope will be used when finding events that should be fired.

By default this scope doesn't do any filtering.

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/custom-d/webhook-registry)
- [All contributors](https://github.com/custom-d/webhook-registry/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
