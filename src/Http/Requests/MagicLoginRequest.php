<?php

namespace Maize\MagicLogin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Maize\MagicLogin\AuthData;

class MagicLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules()
    {
        return [];
    }

    public function data(): array
    {
        return AuthData::fromString(
            $this->get('data')
        )->toArray();
    }

    public function uuid(): string
    {
        return data_get(
            $this->data(),
            'uuid'
        );
    }
}
