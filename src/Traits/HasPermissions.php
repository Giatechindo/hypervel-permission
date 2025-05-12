<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission\Traits;

use Giatechindo\HypervelPermission\Models\Permission;
use Hyperf\Database\Model\Relations\MorphToMany;

trait HasPermissions
{
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.permission'), // Model yang di-relasikan
            'model', // Nama morph
            config('permission.table_names.model_has_permissions'), // Tabel pivot
            config('permission.column_names.model_morph_key'), // Foreign key model ini
            'permission_id' // Foreign key model Permission
        );
    }

    public function givePermissionTo(...$permissions): self
    {
        $permissions = collect($permissions)->flatten()->map(function ($permission) {
            return Permission::where('name', $permission)->firstOrFail();
        });

        $this->permissions()->syncWithoutDetaching($permissions->pluck('id'));

        return $this;
    }

    public function hasPermissionTo(string $permission, string $guardName = 'web'): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->where('guard_name', $guardName)
            ->exists();
    }
}
