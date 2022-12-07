<?php

namespace Maize\MagicLogin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MagicLoginServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-magic-login')
            ->hasConfigFile()
            ->hasMigration('create_magic_logins_table');
    }
}
