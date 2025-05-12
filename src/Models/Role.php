<?php

declare (strict_types = 1);

namespace Giatechindo\HypervelPermission\Models;

use Giatechindo\HypervelPermission\Contracts\Role as RoleContract;
use Hyperf\Database\Model\Concerns\HasUuids;
use Hyperf\DbConnection\Model\Model;
use Ramsey\Uuid\Uuid;

class Role extends Model implements RoleContract
{
    use HasUuids;
    public bool $incrementing = false;

    protected string $keyType = 'string';

    protected array $fillable = ['name', 'guard_name'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('permission.table_names.roles'));

        if (config('permission.use_uuid', false)) {
            $this->attributes['uuid'] = Uuid::uuid4()->toString();
        }
    }

    protected static function boot(): void
    {
        parent::boot();

        // Pastikan UUID dihasilkan jika belum ada
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Uuid::uuid4();
            }
        });
    }
    public function permissions()
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }
}
