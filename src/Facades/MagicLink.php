<?php

namespace Maize\MagicLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string make(\Illuminate\Contracts\Auth\Authenticatable $authenticatable, ?string $redirectUrl = null, \Illuminate\Support\Carbon|int|null $expiration = null, ?string $routeName = null, ?string $guard = null, ?int $loginsLimit = null, array $metadata = [], bool $notify = false)
 * @method static string send(\Illuminate\Contracts\Auth\Authenticatable $authenticatable, ?string $redirectUrl = null, \Illuminate\Support\Carbon|int|null $expiration = null, ?string $routeName = null, ?string $guard = null, ?int $loginsLimit = null, array $metadata = [])
 *
 * @see \Maize\MagicLogin\MagicLink
 */
class MagicLink extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Maize\MagicLogin\MagicLink::class;
    }
}
