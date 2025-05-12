<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission;

use Hyperf\Context\ApplicationContext;
use Giatechindo\HypervelPermission\Exceptions\PermissionAlreadyExists;
use Giatechindo\HypervelPermission\Exceptions\RoleAlreadyExists;
use Giatechindo\HypervelPermission\Models\Permission;
use Giatechindo\HypervelPermission\Models\Role;

class PermissionRegistrar
{
    public function registerPermissions(): void
    {
        // Placeholder for permission registration logic
    }

    public function getPermissionClass(): string
    {
        return config('permission.models.permission');
    }

    public function getRoleClass(): string
    {
        return config('permission.models.role');
    }

    public function createPermission(string $name, string $guardName = 'web'): Permission
    {
        if ($this->permissionExists($name, $guardName)) {
            throw PermissionAlreadyExists::create($name, $guardName);
        }

        return Permission::create([
            'name' => $name,
            'guard_name' => $guardName,
        ]);
    }

    public function createRole(string $name, string $guardName = 'web'): Role
    {
        if ($this->roleExists($name, $guardName)) {
            throw RoleAlreadyExists::create($name, $guardName);
        }

        return Role::create([
            'name' => $name,
            'guard_name' => $guardName,
        ]);
    }

    protected function permissionExists(string $name, string $guardName): bool
    {
        return Permission::where('name', $name)
            ->where('guard_name', $guardName)
            ->exists();
    }

    protected function roleExists(string $name, string $guardName): bool
    {
        return Role::where('name', $name)
            ->where('guard_name', $guardName)
            ->exists();
    }
}