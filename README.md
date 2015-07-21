# Slim Framework HTTP Basic Auth

HTTP Basic Authentication Middleware for Slim Framework.

[![Build Status](https://travis-ci.org/julionc/slim-basic-auth-middleware.svg?branch=master)](https://travis-ci.org/slimphp/Slim-Flash)

This repository contains a Slim Framework HTTP Basic Auth service provider.
This enables you to define Rules that will provide you with basic user authentication based on username and password set.
Also, Realm and Router name set.

## Install

Via Composer

``` bash
$ composer require julionc/slim-basic-auth-middleware
```

Requires Slim 3.0.0 or newer.

## Usage

```php
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

$basic_auth = new \Slim\HttpBasicAuth\Rule('admin', 'admin', null, '/admin');

// Register provider
$container->register($basic_auth);

$app->get('/admin', function ($req, $res, $args) {
    // Show dashboard
});

$app->get('/foo', function ($req, $res, $args) {
    // Show custom page
})->add($basic_auth);

$app->run();
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.