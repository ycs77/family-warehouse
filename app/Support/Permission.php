<?php

namespace App\Support;

use App\User;

class Permission
{
    /**
     * Check the user is has permission.
     *
     * @param  [type] $permission
     * @param  \App\User $user
     * @return boolean
     */
    static public function check($permission, User $user): bool
    {
        $role = $user->role;
        $permissions = config("auth.roles.$role", []);
        return in_array($permission, $permissions);
    }
}
