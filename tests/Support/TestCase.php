<?php

namespace Maize\MagicLogin\Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Maize\MagicLogin\MagicLink;
use Maize\MagicLogin\MagicLoginServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MagicLoginServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('app.key', 'base64:WZWVY1aeHd5rooWQi7hyrXaizzW8VaEwqR3rFlfWfRs=');
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../../database/migrations/create_magic_logins_table.php.stub';
        $migration->up();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        MagicLink::route();

        Route::get('home', fn () => 'home')->middleware('auth');
        Route::get('dashboard', fn () => 'dashboard')->middleware('auth');
    }
}
