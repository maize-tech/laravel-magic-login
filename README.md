# Laravel Magic Login

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-magic-login.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-magic-login)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-magic-login/run-tests?label=tests)](https://github.com/maize-tech/laravel-magic-login/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-magic-login/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/maize-tech/laravel-magic-login/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-magic-login.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-magic-login)

Easily add passwordless authentication into your application.

> This project is a work-in-progress. Code and documentation are currently under development and are subject to change.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-magic-login
```

You can publish the config and migration files and run the migrations with:

```bash
php artisan magic-login:install
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

    /*
    |--------------------------------------------------------------------------
    | Notification class
    |--------------------------------------------------------------------------
    |
    | Here you can specify the fully qualified class name of the magic link
    | email notification.
    | By default, the value is Maize\MagicLogin\Notifications\MagicLinkNotification::class
    */

    'notification' => null,

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

### Basic

To use the package, all you have to do is include the magic link route in your routes file.
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

$user = User::firstOrFail();

$magicLink = MagicLink::make(
    authenticatable: $user
);
```

### Email notifications

#### Send an email notification

Optionally, you may also automatically send an email notification to the given user using the `send` method:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user
);
```

which is equals to using the `make` method with the `notify` parameter set to `true`:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::make(
    authenticatable: $user,
    notify: true
);
```

#### Customize the notification class

If needed, you can customize the email notification.
All you have to do is creating your own notification and override the default `MagicLinkNotification` class:

``` php
use Illuminate\Notifications\Messages\MailMessage;
use Maize\MagicLogin\Notifications\MagicLinkNotification;

class CustomMagicLinkNotification extends MagicLinkNotification
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(__('This is my custom magic link notification message'))
            ->action('Join now', $this->uri);
    }
}
```

Finally, you can update the `notification` attribute under `config/magic-login.php` with the new class path.

### Force single link

When enabled, users can only have one valid link at a time.
This means that when a new link is created, all previously created links are invalidated.

To enable this option, you can set the `force_single` attribute under `config/magic-login.php` to `true`.

### Magic link generator options

The package offers many useful parameters for the `make` method to allow you customizing every single magic link:

- [`Redirect url`](#redirect-url)
- [`Expiration time`](#expiration-time)
- [`Route name`](#route-name)
- [`Authentication guard`](#authentication-guard)
- [`Logins limit`](#logins-limit)

#### Redirect url

You can provide a redirect url used after authenticating the user:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user,
    redirectUrl: 'yourapplication.test/your-path'
);
```

When not provided, the default value defined in `redirect_url` attribute under `config/magic-login.php` will be used.

#### Expiration time

You can define the amount of time before a magic link expires by providing a carbon instance or an integer with the amount of minutes:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user,
    expiration: now()->addDays(10), // the link will expire in 10 days
);

$magicLink = MagicLink::send(
    authenticatable: $user,
    expiration: 60, // the link will expire in 1 hour (60 minutes)
);
```

When not provided, the default value defined in `expiration` attribute under `config/magic-login.php` will be used.

#### Route name

You can define the route name used to generate the magic link:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user,
    routeName: 'magic-link',
);
```

When not provided, the default value defined in `route.name` attribute under `config/magic-login.php` will be used.

#### Authentication guard

You can define the authentication guard used to authenticate the user:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user,
    guard: 'api' // the 'api' auth guard will be used
);
```

When not provided, the default value defined in `guard` attribute under `config/magic-login.php` will be used.

#### Logins limit

You can define the amount of times a single link can be used before expiring.
The value can be either -1, which lets the user login indefinitely, or any number greater than or equal to 1:

``` php
use App\Models\User;
use Maize\MagicLogin\Facades\MagicLink;

$user = User::firstOrFail();

$magicLink = MagicLink::send(
    authenticatable: $user,
    loginsLimit: 5 // the link can be used 5 times at max
);

$magicLink = MagicLink::send(
    authenticatable: $user,
    loginsLimit: -1 // the link can be used an infinite amount of times
);
```

When not provided, the default value defined in `logins_limit` attribute under `config/magic-login.php` will be used.

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
