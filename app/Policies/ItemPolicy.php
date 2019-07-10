<?php

namespace App\Policies;

use App\Support\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the category.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return Permission::check('items-view', $user);
    }

    /**
     * Determine whether the user can edit the category.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function edit(User $user)
    {
        return Permission::check('items-edit', $user);
    }
}
