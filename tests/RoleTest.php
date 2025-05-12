<?php

declare(strict_types=1);

namespace Giatechindo\HypervelPermission\Tests;

use Giatechindo\HypervelPermission\Models\Role;

class RoleTest extends TestCase
{
    public function testCreateRole()
    {
        // Create role with name 'admin'
        $role = Role::create(['name' => 'admin']);

        // Ensure role was created
        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('admin', $role->name);

        // Check key based on UUID setting
        if (config('permission.use_uuid', false)) {
            $this->assertNotEmpty($role->uuid, 'UUID should be generated');
            $this->assertIsString($role->getKey(), 'Primary key should be a string');
        } else {
            $this->assertIsInt($role->getKey(), 'Primary key should be an integer');
        }
    }
}