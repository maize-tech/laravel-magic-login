<?php

namespace Maize\MagicLogin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Maize\MagicLogin\Support\AuthData;
use Maize\MagicLogin\Support\Config;

class MagicLink
{
    public function make(
        Authenticatable $authenticatable,
        ?string $redirectUrl = null,
        Carbon|int|null $expiration = null,
        ?string $routeName = null,
        ?string $guard = null,
        ?int $loginsLimit = null,
        array $metadata = [],
        bool $notify = false
    ): string {
        if (Config::forceSingle()) {
            $this->revokeAll($authenticatable);
        }

        $model = Config::getModel()->create([
            'authenticatable_id' => $authenticatable->getKey(),
            'authenticatable_type' => $authenticatable->getMorphClass(),
            'logins' => 0,
            'logins_limit' => Config::getLoginLimits($loginsLimit),
            'guard' => Config::getGuard($guard),
            'redirect_url' => Config::getRedirectUrl($redirectUrl),
            'expires_at' => Config::getExpiration($expiration),
            'metadata' => $metadata,
        ]);

        $uri = URL::temporarySignedRoute(
            name: Config::getRouteName($routeName),
            expiration: Config::getExpiration($expiration),
            parameters: [
                'data' => AuthData::fromArray([
                    'uuid' => $model->uuid,
                ])->toString(),
            ],
        );

        if ($notify) {
            Config::getSendNotificationAction()(
                uri: $uri,
                model: $model
            );
        }

        return $uri;
    }

    public function send(
        Authenticatable $authenticatable,
        ?string $redirectUrl = null,
        ?Carbon $expiration = null,
        ?string $routeName = null,
        ?string $guard = null,
        ?int $loginsLimit = null,
    ): string {
        return static::make(
            authenticatable: $authenticatable,
            redirectUrl: $redirectUrl,
            expiration: $expiration,
            routeName: $routeName,
            guard: $guard,
            loginsLimit: $loginsLimit,
            notify: true
        );
    }

    public function route()
    {
        Route::match(
            methods: Config::getRouteMethods(),
            uri: Config::getRouteUri(),
            action: Config::getRouteController()
        )
            ->name(Config::getRouteName())
            ->middleware(Config::getRouteMiddleware());
    }

    public function revokeAll(Authenticatable $authenticatable): void
    {
        Config::getModel()
            ->query()
            ->whereMorphedTo('authenticatable', $authenticatable)
            ->delete();
    }
}
