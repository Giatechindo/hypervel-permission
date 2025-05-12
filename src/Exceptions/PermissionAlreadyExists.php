<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission\Exceptions;

use RuntimeException;

class PermissionAlreadyExists extends RuntimeException
{
    public static function create(string $name, string $guardName): self
    {
        return new self("A permission `{$name}` already exists for guard `{$guardName}`.");
    }
}