# Laravel Magic Login

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-magic-login.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-magic-login)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-magic-login/run-tests?label=tests)](https://github.com/maize-tech/laravel-magic-login/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-magic-login/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/maize-tech/laravel-magic-login/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-magic-login.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-magic-login)

Easily add passwordless authentication into your application.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-magic-login
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="magic-login-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="magic-login-config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Magic Login model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the magic login
    | model.
    | By default, the value is Maize\MagicLogin\Models\MagicLogin::class
    |
    */

    'model' => null,

    /*
    |--------------------------------------------------------------------------
    | Expiration time
    |--------------------------------------------------------------------------
    |
    | Here you may specify the amount of minutes before the magic login link
    | expires.
    | By default, the value is 120 minutes (2 hours).
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the guard you want to use to authenticate the user.
    | The guard name must be defined in your application's auth.php config file.
    | By default, the value is 'web'.
    |
    */

    'guard' => null,

    /*
    |--------------------------------------------------------------------------
    | Default redirect url
    |--------------------------------------------------------------------------
    |
    | Here you may specify the redirect url used by default if none is specified
    | when creating the magic link.
    |
    */

    'redirect_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Exception class
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default exception class used for all package
    | related exceptions.
    | Useful if you need to customize the http status code in case an exception
    | is thrown during the magic login process.
    | By default, the value is Illuminate\Routing\Exceptions\InvalidSignatureException::class
    |
    */

    'exception' => null,

    /*
    |--------------------------------------------------------------------------
    | Force single link
    |--------------------------------------------------------------------------
    |
    | Here you can specify whether a user can only have one valid magic link
    | at a time or not.
    | If true, when you generate a new magic link for a specific user all
    | previously generated links will be revoked.
    | By default, the value is true.
    |
    */

    'force_single' => null,

    /*
    |--------------------------------------------------------------------------
    | Logins limit
    |--------------------------------------------------------------------------
    |
    | Here you can specify the amount of logins a user can perform with the
    | same magic link.
    | Can be either -1, which lets the user login indefinitely, or any number
    | greater than or equal to 1.
    | By default, the value is -1.
    |
    */

    'logins_limit' => null,

    /*
    |--------------------------------------------------------------------------
    | Send Notification Action
    |--------------------------------------------------------------------------
    |
    | Here you can specify the fully qualified class name of a custom action
    | used to send the magic login email notification.
    | By default, the value is Maize\MagicLogin\Actions\SendMagicLinkAction::class
    */

    'send_notification_action' => null,

    'route' => [

        /*
        |--------------------------------------------------------------------------
        | Route method
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's allowed methods.
        | By default, the value is 'GET'.
        |
        */

        'methods' => null,

        /*
        |--------------------------------------------------------------------------
        | Route URI
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's uri.
        | By default, the value is 'magic-login'.
        |
        */

        'uri' => null,

        /*
        |--------------------------------------------------------------------------
        | Route name
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's name.
        | By default, the value is 'magic-login'.
        |
        */

        'name' => null,

        /*
        |--------------------------------------------------------------------------
        | Route controller
        |--------------------------------------------------------------------------
        |
        | Here you may specify the fully qualified class name of a custom controller
        | class used to handle the magic login request.
        | By default, the value is Maize\MagicLogin\Http\Controllers\MagicLoginController::class.
        |
        */

        'controller' => null,

        /*
        |--------------------------------------------------------------------------
        | Route middlewares
        |--------------------------------------------------------------------------
        |
        | Here you may specify the list of middlewares used by the magic login route.
        | By default, the value is Maize\MagicLogin\Http\Middleware\ValidateSignature::class.
        |
        */

        'middleware' => null,

    ],

];
```

## Usage

To use the package, all you have to do is including the magic link route in your routes file.
By default, you should include it under `routes/web.php`:

``` php
use Maize\MagicLogin\Facades\MagicLink;

MagicLink::route();
```

That's it!
Once done, you can already generate an invitation link to any model extending the `Authenticatable` class using the `make` method:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$magicLink = MagicLink::make(
    authenticatable: User::firstOrFail()
);
```

Optionally, you may also automatically send a notification email to the given user using the `send` method:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$magicLink = MagicLink::send(
    authenticatable: User::firstOrFail()
);
```

which is equals to use the `make` method with the `notify` parameter set to `true`:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$magicLink = MagicLink::make(
    authenticatable: User::firstOrFail(),
    notify: true
);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Enrico De Lazzari](https://github.com/enricodelazzari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
