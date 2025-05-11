<?php

namespace Giatechindo\HypervelPermission\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('hypervel-permission.table_names.roles'));
    }

    public function permissions()
    {
        return $this->belongsToMany(
            config('hypervel-permission.models.permission'),
            config('hypervel-permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }
}