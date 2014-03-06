# HTTP Basic Auth Middleware for Slim

HTTP Basic Authentication Middleware for Slim Framework.

## Install

Update your `composer.json` manifest to require the `julionc/slim-basic-auth-middleware` package (see below).
Run `composer install` or `composer update` to update your local vendor folder.

```javascript
{
    "require": {
        "julionc/slim-basic-auth-middleware": "dev-master",
    }
}
```

## HttpBasic

This will provide you with basic user Authentication based on username and password set.
Also, Realm and Router name set.

### How to use

```php
use \Slim\Slim;
use \Slim\Middleware\HttpBasicAuth;

$app = new Slim();
$app->add(new HttpBasicAuth('admin', 'admin', null, '/admin'));
```

## License

MIT