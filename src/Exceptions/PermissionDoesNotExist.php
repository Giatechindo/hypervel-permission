<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission\Exceptions;

use RuntimeException;

class PermissionDoesNotExist extends RuntimeException
{
    public static function create(string $name, string $guardName): self
    {
        return new self("Permission `{$name}` does not exist for guard `{$guardName}`.");
    }
}