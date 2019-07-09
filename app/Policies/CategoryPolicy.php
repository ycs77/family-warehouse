<?php

namespace App\Policies;

use App\Support\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
        return Permission::check('categories-view', $user);
    }

    /**
     * Determine whether the user can edit the category.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function edit(User $user)
    {
        return Permission::check('categories-edit', $user);
    }
}
