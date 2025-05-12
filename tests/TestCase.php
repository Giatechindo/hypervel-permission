<?php

declare (strict_types = 1);

namespace Giatechindo\HypervelPermission\Tests;

use Dotenv\Dotenv;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\ConnectionResolver;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Connectors\ConnectionFactory;
use Hyperf\Database\Exception\QueryException;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSource;
use Hypervel\Config\Contracts\Repository as HypervelRepositoryContract;
use Hypervel\Config\Repository as Config;
use Hypervel\Config\Repository as HypervelRepository;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        echo "\n🔵 Setting up test environment...\n";

        if (file_exists(__DIR__ . '/../../.env')) {
            Dotenv::createImmutable(__DIR__ . '/../../')->load();
            echo "✅ .env file loaded successfully\n";
        } else {
            echo "⚠️  No .env file found, using default values\n";
        }

        $config = new Config([
            'databases'  => [
                'default' => [
                    'driver'    => 'mysql',
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'database'  => env('DB_DATABASE', 'db_tes_hypervel'),
                    'username'  => env('DB_USERNAME', 'root'),
                    'password'  => env('DB_PASSWORD', 'password'),
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                ],
            ],
            'permission' => [
                'use_uuid'    => true, // Enable UUID support
                'table_names' => [
                    'roles'                 => 'roles',
                    'permissions'           => 'permissions',
                    'model_has_permissions' => 'model_has_permissions',
                    'model_has_roles'       => 'model_has_roles',
                    'role_has_permissions'  => 'role_has_permissions',
                ],
                'models'      => [
                    'permission' => \Giatechindo\HypervelPermission\Models\Permission::class,
                ],
            ],
        ]);

        $container = new Container(new DefinitionSource([]));
        echo "✅ Dependency container created\n";

        $container->set(ConfigInterface::class, $config);
        $container->set(HypervelRepositoryContract::class, new HypervelRepository($config->all()));
        $container->set(StdoutLoggerInterface::class, Mockery::mock(StdoutLoggerInterface::class));
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldIgnoreMissing();
        $container->set(EventDispatcherInterface::class, $eventDispatcher);
        $container->set(Filesystem::class, new Filesystem());
        echo "✅ Dependencies bound to container\n";

        ApplicationContext::setContainer($container);

        try {
            $factory    = new ConnectionFactory($container);
            $connection = $factory->make($config->get('databases.default'));
            echo "✅ Database connection established\n";
        } catch (\Throwable $e) {
            echo "❌ Failed to connect to database: " . $e->getMessage() . "\n";
            throw $e;
        }

        $resolver = new ConnectionResolver(['default' => $connection]);
        $resolver->setDefaultConnection('default');
        $container->set(ConnectionResolverInterface::class, $resolver);

        // Drop existing tables before migrations
        $this->dropTables();

        $this->runMigrations();
    }

    protected function dropTables(): void
    {
        $tables = [
            'role_has_permissions',
            'model_has_roles',
            'model_has_permissions',
            'roles',
            'permissions',
        ];

        foreach ($tables as $table) {
            try {
                Db::statement("DROP TABLE IF EXISTS {$table}");
                echo "✅ Dropped table: {$table}\n";
            } catch (\Throwable $e) {
                echo "⚠️ Failed to drop table {$table}: " . $e->getMessage() . "\n";
            }
        }
    }

    protected function runMigrations(): void
    {
        echo "\n🔵 Running migrations...\n";

        $migrations = [
            __DIR__ . '/../database/migrations/2024_01_01_000000_create_permission_tables.php',
            __DIR__ . '/../database/migrations/2024_01_01_000001_add_uuid_support.php',
        ];

        foreach ($migrations as $migration) {
            if (! file_exists($migration)) {
                echo "❌ Migration file not found: {$migration}\n";
                throw new \RuntimeException("Migration file not found: {$migration}");
            }

            $migrationName = basename($migration);
            echo "🔄 Processing migration: {$migrationName}... ";

            try {
                // Clear any cached file state
                clearstatcache(true, $migration);
                $migrationClass = require $migration; // Revert to require for fresh inclusion
                if (! is_object($migrationClass)) {
                    echo "❌ Invalid migration class: {$migrationName}\n";
                    throw new \RuntimeException("Migration file did not return a valid class: {$migration}");
                }
                $instance = is_string($migrationClass) ? new $migrationClass() : $migrationClass;
                $instance->up();
                echo "✅ Success\n";
            } catch (QueryException $e) {
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    $tableName = $this->extractTableNameFromError($e->getMessage());
                    echo "⚠️ Skipped (Table '{$tableName}' already exists)\n";
                } else {
                    echo "❌ Failed: " . $e->getMessage() . "\n";
                    throw $e;
                }
            } catch (\Throwable $e) {
                echo "❌ Failed: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
    }

    protected function tearDown(): void
    {
        echo "\n🔵 Cleaning up test environment...\n";

        // Ensure tables are dropped even if migrations fail
        try {
            $this->dropTables();
        } catch (\Throwable $e) {
            echo "⚠️ Failed to drop tables during cleanup: " . $e->getMessage() . "\n";
        }

        try {
            Db::disconnect('default');
            echo "✅ Database connection closed\n";
        } catch (\Throwable $e) {
            echo "⚠️ Failed to disconnect database: " . $e->getMessage() . "\n";
        }

        Mockery::close();
        parent::tearDown();

        echo "\n🏁 Test cleanup completed\n";
    }

    private function extractTableNameFromError(string $errorMessage): string
    {
        if (preg_match("/Table '(.+?)' already exists/", $errorMessage, $matches)) {
            return $matches[1];
        }
        return 'unknown_table';
    }
}

// Helper function for config
if (! function_exists('config')) {
    function config($key = null, $default = null)
    {
        $repository = ApplicationContext::getContainer()->get(HypervelRepositoryContract::class);
        return $key === null ? $repository : $repository->get($key, $default);
    }
}
