<?php

namespace Maize\MagicLogin\Http\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature as BaseValidateSignature;
use Maize\MagicLogin\Support\Config;

class ValidateSignature extends BaseValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $relative
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, $relative = null)
    {
        try {
            return parent::handle($request, $next, $relative);
        } catch (InvalidSignatureException $e) {
            throw Config::getException();
        }
    }
}
