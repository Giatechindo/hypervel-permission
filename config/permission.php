<?php
// config/permission.php

declare(strict_types=1);

return [
    'models' => [
        'permission' => \Giatechindo\HypervelPermission\Models\Permission::class,
        'role' => \Giatechindo\HypervelPermission\Models\Role::class,
    ],
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],
    'column_names' => [
        'model_morph_key' => 'model_id',
    ],
    'use_uuid' => env('PERMISSION_USE_UUID', false),
];