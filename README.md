![Vemetric PHP SDK](https://github.com/user-attachments/assets/7f2fe2ba-40b9-4d2e-8e55-eadddff5f310)

# The Vemetric SDK for PHP

Learn more about the Vemetric PHP SDK in the [official docs](https://vemetric.com/docs/sdks/php).

You can also checkout the package on [Packagist](https://packagist.org/packages/vemetric/vemetric-php).

[![Packagist Version](https://img.shields.io/packagist/v/vemetric/vemetric-php)](https://packagist.org/packages/vemetric/vemetric-php)

## Installation

```bash
composer require vemetric/vemetric-php
```

## Usage

```php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Vemetric\Vemetric;

$vemetric = new Vemetric([
  'token' => 'YOUR_PROJECT_TOKEN',
]);

// Track an event
$vemetric->trackEvent('SignupCompleted', [
  'userIdentifier' => 'user-id',
  'userDisplayName' => 'John Doe',
  'eventData'      => ['key' => 'value'],
]);

// Update user data
$vemetric->updateUser([
  'userIdentifier' => 'user-id',
  'userData'       => [
    'set' => ['key1' => 'value1'],
    'setOnce' => ['key2' => 'value2'],
    'unset' => ['key3'],
  ],
]);
```

## Configuration

The client can be configured with the following options:

```php
$vemetric = new Vemetric([
  'token' => 'YOUR_PROJECT_TOKEN', // Required
  'host' => 'https://hub.vemetric.com', // Optional, defaults to https://hub.vemetric.com
]);
```
