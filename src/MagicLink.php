<?php

namespace Maize\MagicLogin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Maize\MagicLogin\Support\Config;

class MagicLink
{
    public static function make(
        Authenticatable $authenticatable,
        string $redirectUrl,
        ?Carbon $expiration = null,
        ?string $routeName = null,
        ?string $guard = null,
        ?int $loginsLimit = null,
    ): string {
        if (Config::forceSingle()) {
            static::revokeAll($authenticatable);
        }

        $model = Config::getModel()->create([
            'authenticatable_id' => $authenticatable->getKey(),
            'authenticatable_type' => $authenticatable->getMorphClass(),
            'logins' => 0,
            'logins_limit' => Config::getLoginLimits($loginsLimit),
            'guard' => Config::getGuard($guard),
            'redirect_url' => Config::getRedirectUrl($redirectUrl),
            'expires_at' => Config::getExpiration($expiration),
        ]);

        return URL::temporarySignedRoute(
            name: Config::getRouteName($routeName),
            expiration: Config::getExpiration($expiration),
            parameters: [
                'data' => AuthData::fromArray([
                    'uuid' => $model->uuid,
                ])->toString(),
            ],
        );
    }

    public static function route()
    {
        Route::match(
            methods: Config::getRouteMethods(),
            uri: Config::getRouteUri(),
            action: Config::getRouteController()
        )
            ->name(Config::getRouteName())
            ->middleware(Config::getRouteMiddleware());
    }

    public static function revokeAll(Authenticatable $authenticatable): void
    {
        Config::getModel()
            ->query()
            ->whereMorphedTo('authenticatable', $authenticatable)
            ->delete();
    }
}
