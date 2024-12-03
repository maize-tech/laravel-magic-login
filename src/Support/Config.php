<?php

namespace Maize\MagicLogin\Support;

use Exception;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maize\MagicLogin\Actions\SendMagicLinkAction;
use Maize\MagicLogin\Http\Controllers\MagicLoginController;
use Maize\MagicLogin\Http\Middleware\ValidateSignature;
use Maize\MagicLogin\Models\MagicLogin;
use Maize\MagicLogin\Notifications\MagicLinkNotification;

class Config
{
    public static function getModel(): MagicLogin
    {
        $model = config('magic-login.model')
            ?? MagicLogin::class;

        return new $model;
    }

    public static function getExpiration(Carbon|int|null $expiration = null): Carbon
    {
        $expiration ??= config('magic-login.expiration')
            ?? 120;

        if ($expiration instanceof Carbon) {
            return $expiration;
        }

        return now()->addMinutes($expiration);
    }

    public static function getGuard(?string $guard = null): string
    {
        return $guard
            ?? config('magic-login.guard')
            ?? 'web';
    }

    public static function getRedirectUrl(?string $redirectUrl = null): string
    {
        return $redirectUrl
            ?? config('magic-login.redirect_url')
            ?? throw new Exception('The redirect url is required.');
    }

    public static function getLoginLimits(?int $loginLimits = null): int
    {
        $loginLimits ??= config('magic-login.logins_limit') ?? -1;

        if ($loginLimits < -1) {
            throw new Exception('The logins limit cannot be less than -1');
        }

        if ($loginLimits === 0) {
            throw new Exception('The logins limit cannot be equal to 0');
        }

        return $loginLimits;
    }

    public static function forceSingle(): bool
    {
        return config('magic-login.force_single') ?? true;
    }

    public static function getSendNotificationAction(): SendMagicLinkAction
    {
        $notification = config('magic-login.send_notification_action')
            ?? SendMagicLinkAction::class;

        return app($notification);
    }

    public static function getNotificationClass(): string
    {
        return config('magic-login.notification') ?? MagicLinkNotification::class;
    }

    public static function getRouteMethods(): array
    {
        $method = config('magic-login.route.methods')
            ?? 'GET';

        return Arr::wrap($method);
    }

    public static function getRouteUri(?string $uri = null): string
    {
        return $uri
            ?? config('magic-login.route.uri')
            ?? 'magic-login';
    }

    public static function getRouteName(?string $name = null): string
    {
        return $name
            ?? config('magic-login.route.name')
            ?? 'magic-login';
    }

    public static function getRouteController(?string $controller = null): string
    {
        return $controller
            ?? config('magic-login.route.controller')
            ?? MagicLoginController::class;
    }

    public static function getRouteMiddleware(array|string|null $middleware = null): array
    {
        $middleware ??= config('magic-login.route.middleware')
            ?? ValidateSignature::class;

        return Arr::wrap($middleware);
    }

    public static function getException(): Exception
    {
        $exception = config('magic-login.exception')
            ?? InvalidSignatureException::class;

        return new $exception;
    }
}
