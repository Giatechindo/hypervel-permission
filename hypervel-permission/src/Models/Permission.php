<?php

namespace Giatechindo\HypervelPermission\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('hypervel-permission.table_names.permissions'));
    }

    public function roles()
    {
        return $this->belongsToMany(
            config('hypervel-permission.models.role'),
            config('hypervel-permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }
}