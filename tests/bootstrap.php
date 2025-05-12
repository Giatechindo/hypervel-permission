<?php

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSource;
use Hyperf\Config\Config;

// Define BASE_PATH for the test environment
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Autoload dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize Hyperf DI container
$container = new Container(new DefinitionSource([]));

// Create and bind configuration manually
$config = new Config([
    'permission' => require __DIR__ . '/../config/permission.php',
    'databases' => [
        'default' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'charset' => 'utf8',
        ],
    ],
]);
$container->set(ConfigInterface::class, $config);