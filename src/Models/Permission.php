<?php

declare (strict_types = 1);

namespace Giatechindo\HypervelPermission\Models;

use Giatechindo\HypervelPermission\Contracts\Permission as PermissionContract;
use Hyperf\DbConnection\Model\Model;
use Hypervel\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class Permission extends Model implements PermissionContract
{
    use HasUuids;

    public bool $incrementing = false;

    protected string $keyType = 'string';

    protected array $fillable = ['name', 'guard_name'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('permission.table_names.permissions'));

        if (config('permission.use_uuid', false)) {
            $this->attributes['uuid'] = Uuid::uuid4()->toString();
        }
    }

    public function roles()
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }
}
