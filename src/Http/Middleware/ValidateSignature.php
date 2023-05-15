<?php

namespace Maize\MagicLogin\Http\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature as BaseValidateSignature;
use Maize\MagicLogin\Support\Config;

class ValidateSignature extends BaseValidateSignature
{
    public function handle($request, Closure $next, ...$args)
    {
        try {
            return parent::handle($request, $next, ...$args);
        } catch (InvalidSignatureException $e) {
            throw Config::getException();
        }
    }
}
