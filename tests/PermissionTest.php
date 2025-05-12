<?php

namespace Giatechindo\HypervelPermission\Tests;

use Giatechindo\HypervelPermission\Models\Permission;

class PermissionTest extends TestCase
{
    public function testCreatePermission()
    {
        $permission = Permission::create(['name' => 'edit-post']);
        $this->assertInstanceOf(Permission::class, $permission);
        $this->assertEquals('edit-post', $permission->name);
    }
}