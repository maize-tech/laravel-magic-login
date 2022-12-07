<?php

namespace Maize\MagicLogin\Tests\Support\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidSignatureTestException extends HttpException
{
    public function __construct()
    {
        parent::__construct(418, 'Invalid signature (418).');
    }
}
