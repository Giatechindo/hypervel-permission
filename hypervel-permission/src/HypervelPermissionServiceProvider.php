<?php

namespace Giatechindo\HypervelPermission;

use Hypervel\Providers\ServiceProvider;

class HypervelPermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publikasi konfigurasi
        $this->publishes([
            __DIR__.'/../config/hypervel-permission.php' => $this->app->configPath('hypervel-permission.php'),
        ], 'config');

        // Publikasi migrasi
        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], 'migrations');

        // Daftarkan middleware
        $this->app->get('router')->aliasMiddleware('role', Middleware\RoleMiddleware::class);
        $this->app->get('router')->aliasMiddleware('permission', Middleware\PermissionMiddleware::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/hypervel-permission.php', 'hypervel-permission'
        );
    }
}