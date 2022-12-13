<?php

namespace Maize\MagicLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Maize\MagicLogin\MagicLink
 */
class MagicLink extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Maize\MagicLogin\MagicLink::class;
    }
}
