<?php

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
    'identifier_type' => 'id', // 'id' untuk integer, 'uuid' untuk UUID
];