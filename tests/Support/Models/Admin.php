<?php

namespace Maize\MagicLogin\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Maize\MagicLogin\Tests\Support\Factories\AdminFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        //
    ];

    protected static function newFactory(): Factory
    {
        return AdminFactory::new();
    }
}
