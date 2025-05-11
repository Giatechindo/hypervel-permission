<?php

namespace Giatechindo\HypervelPermission\Traits;

use Giatechindo\HypervelPermission\Models\Role;
use Giatechindo\HypervelPermission\Models\Permission;

trait HasRoles
{
    public function roles()
    {
        return $this->morphToMany(
            config('hypervel-permission.models.role'),
            'model',
            config('hypervel-permission.table_names.model_has_roles'),
            'model_id',
            'role_id'
        );
    }

    public function permissions()
    {
        return $this->morphToMany(
            config('hypervel-permission.models.permission'),
            'model',
            config('hypervel-permission.table_names.model_has_permissions'),
            'model_id',
            'permission_id'
        );
    }

    public function assignRole($role)
    {
        $role = is_string($role) ? Role::whereName($role)->firstOrFail() : $role;
        $this->roles()->syncWithoutDetaching($role);
        return $this;
    }

    public function hasRole($role)
    {
        return is_string($role)
            ? $this->roles->contains('name', $role)
            : $this->roles->contains('id', $role->id);
    }

    public function givePermissionTo($permission)
    {
        $permission = is_string($permission) ? Permission::whereName($permission)->firstOrFail() : $permission;
        $this->permissions()->syncWithoutDetaching($permission);
        return $this;
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    protected function hasDirectPermission($permission)
    {
        return is_string($permission)
            ? $this->permissions->contains('name', $permission)
            : $this->permissions->contains('id', $permission->id);
    }

    protected function hasPermissionViaRole($permission)
    {
        $permission = is_string($permission) ? Permission::whereName($permission)->firstOrFail() : $permission;
        return $this->roles->flatMap->permissions->contains('id', $permission->id);
    }
}