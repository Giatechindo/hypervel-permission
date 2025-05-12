<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission;

use Hyperf\Contract\ContainerInterface;

class PermissionServiceProvider
{
    protected ContainerInterface $container;

    public function __invoke(ContainerInterface $container): void
    {
        $this->container = $container;

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/permission.php' => BASE_PATH . '/config/autoload/permission.php',
        ]);

        // Publish migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register PermissionRegistrar
        $container->set(PermissionRegistrar::class, new PermissionRegistrar());
    }

    protected function publishes(array $publishes): void
    {
        // In a test environment, simulate publishing by merging configs
        foreach ($publishes as $source => $dest) {
            if (file_exists($source)) {
                $config = require $source;
                $this->config()->set(basename($dest, '.php'), $config);
            }
        }
    }

    protected function loadMigrationsFrom(string $path): void
    {
        // In tests, migrations are loaded manually by TestCase
    }

    protected function config()
    {
        return $this->container->get(\Hyperf\Contract\ConfigInterface::class);
    }
}