<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission;

use Hypervel\Support\ServiceProvider;
use Giatechindo\HypervelPermission\PermissionRegistrar;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Gunakan $this->app karena ServiceProvider Hypervel sudah punya container di sana
        $this->app->set(PermissionRegistrar::class, new PermissionRegistrar());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/permission.php' => config_path('permission.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->mergeConfigFrom(__DIR__ . '/../config/permission.php', 'permission');
    }
}