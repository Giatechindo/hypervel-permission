<?php

namespace Giatechindo\HypervelPermission;

use Illuminate\Support\ServiceProvider;
use Giatechindo\HypervelPermission\Middleware\RoleMiddleware;
use Giatechindo\HypervelPermission\Middleware\PermissionMiddleware;

class HypervelPermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publikasi konfigurasi
        $this->publishes([
            __DIR__.'/../config/hypervel-permission.php' => config_path('hypervel-permission.php'),
        ], 'hypervel-permission-config');

        // Publikasi migrasi
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'hypervel-permission-migrations');

        // Daftarkan middleware
        $this->app->get('router')->aliasMiddleware('role', RoleMiddleware::class);
        $this->app->get('router')->aliasMiddleware('permission', PermissionMiddleware::class);
    }

    public function register()
    {
        // Merge konfigurasi
        $this->mergeConfigFrom(
            __DIR__.'/../config/hypervel-permission.php', 'hypervel-permission'
        );
    }
}