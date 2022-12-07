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
];
```

## Usage

```php
$magicLogin = new Maize\MagicLogin();
echo $magicLogin->echoPhrase('Hello, Maize!');
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
