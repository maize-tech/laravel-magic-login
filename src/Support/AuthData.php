<?php

namespace Maize\MagicLogin\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class AuthData
{
    public function __construct(
        public readonly array $data
    ) {}

    public static function fromString(string $string): self
    {
        return new self(
            static::decrypt($string)
        );
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toString(): string
    {
        return $this->encrypt(
            $this->data
        );
    }

    public function toArray(): array
    {
        return $this->data;
    }

    protected static function encrypt(array $data): string
    {
        return Crypt::encryptString(
            json_encode($data)
        );
    }

    protected static function decrypt(string $string): array
    {
        try {
            return json_decode(
                json: Crypt::decryptString($string),
                associative: true
            );
        } catch (DecryptException) {
            throw Config::getException();
        }
    }
}
