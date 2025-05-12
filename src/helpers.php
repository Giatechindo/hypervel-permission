<?php

declare(strict_types=1);

use Giatechindo\HypervelPermission\PermissionRegistrar;
use Hyperf\Context\ApplicationContext;

if (! function_exists('permission')) {
    function permission(): PermissionRegistrar
    {
        return ApplicationContext::getContainer()->get(PermissionRegistrar::class);
    }
}