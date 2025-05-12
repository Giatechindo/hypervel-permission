<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission\Traits;

use Giatechindo\HypervelPermission\Models\Role;
use Hyperf\Database\Model\Relations\MorphToMany;

trait HasRoles
{
    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }

    public function assignRole(...$roles): self
    {
        $roles = collect($roles)->flatten()->map(function ($role) {
            return Role::where('name', $role)->firstOrFail();
        });

        $this->roles()->syncWithoutDetaching($roles->pluck('id'));

        return $this;
    }

    public function hasRole(string $role, string $guardName = 'web'): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->where('guard_name', $guardName)
            ->exists();
    }
}