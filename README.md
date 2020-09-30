# Webhook Registry

[![Build Status](https://travis-ci.org/custom-d/webhook-registry.svg?branch=master)](https://travis-ci.org/custom-d/webhook-registry)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/custom-d/webhook-registry/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/custom-d/webhook-registry/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/custom-d/webhook-registry/badge.svg?branch=master)](https://coveralls.io/github/custom-d/webhook-registry?branch=master)

[![Packagist](https://img.shields.io/packagist/v/custom-d/webhook-registry.svg)](https://packagist.org/packages/custom-d/webhook-registry)
[![Packagist](https://poser.pugx.org/custom-d/webhook-registry/d/total.svg)](https://packagist.org/packages/custom-d/webhook-registry)
[![Packagist](https://img.shields.io/packagist/l/custom-d/webhook-registry.svg)](https://packagist.org/packages/custom-d/webhook-registry)

Package description: CHANGE ME

@customD version


## Installation

Install via composer
```bash
composer require custom-d/webhook-registry
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
CustomD\WebhookRegistry\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
CustomD\WebhookRegistry\Facades\WebhookRegistry::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="CustomD\WebhookRegistry\ServiceProvider" --tag="config"
```

## Usage

Implement the `CustomD\WebhookRegistry\Contracts\ShouldDeliverWebhooks` contract on any event you'd like to trigger a web-hook. This contract requires a `getWebhookPayload` method to be defined on your event, which will provide the payload details.

Your payload must define a `body`, but can also define `tags` and `meta` information for passing to `Spatie\WebhookServer\WebhookCall`.

```
    /**
     * Get the webhook payload
     *
     * @return string
     */
    public function getWebhookPayload(): array
    {
        $user = request()->user();

        return [
            'body' => [
                'status' => $this->status,
                'triggered_by' => $user && $user->toArray()
            ]
        ];
    }
```

Create webhook endpoints uing `CustomD\WebhookRegistry\Model\WebhookEndpoint` or using the facade `WebhookRegistry::registerEndpoint` method, and associate a webhook event to an endpoint with the `CustomD\WebhookRegistry\Model\WebhookEvent` model, or using the facade `WebhookRegistry::registerEvent`.

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/custom-d/webhook-registry)
- [All contributors](https://github.com/custom-d/webhook-registry/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
