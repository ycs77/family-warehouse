<?php

namespace App\Support;

use App\User;

class Permission
{
    static public function check($permission, User $user)
    {
        $role = $user->role;
        $permissions = config("auth.roles.$role", []);
        return in_array($permission, $permissions);
    }
}
