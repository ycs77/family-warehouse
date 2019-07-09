<?php

namespace Tests\Unit\Support;

use App\Support\Permission;
use App\User;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('auth.roles', [
            'user' => [
                'permission-1',
            ],
            'admin' => [
                'permission-1',
                'permission-2',
            ],
        ]);
    }

    public function testCheckAdminPermission()
    {
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $this->assertTrue(Permission::check('permission-1', $user));
        $this->assertTrue(Permission::check('permission-2', $user));
        $this->assertFalse(Permission::check('permission-not-found', $user));
    }

    public function testCheckUserPermission()
    {
        $user = factory(User::class)->create([
            'role' => 'user',
        ]);

        $this->assertTrue(Permission::check('permission-1', $user));
        $this->assertFalse(Permission::check('permission-2', $user));
    }
}
