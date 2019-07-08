<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept before executing policy
     *
     * @param  \App\User  $user
     * @param  mixed  $ability
     * @return void
     */
    public function before($user, $ability)
    {
        if ($this->admin($user)) {
            return true;
        }
    }

    /**
     * Determine whether the user is administrator.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function admin(User $user)
    {
        return $user->permission === 'admin';
    }

    /**
     * Determine whether the user is administrator.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function user(User $user)
    {
        return $user->permission === 'user';
    }
}
