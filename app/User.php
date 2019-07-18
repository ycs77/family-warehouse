<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isCantDeprivation()
    {
        if (Auth::user()->id === $this->id || $this->username === 'admin') {
            return true;
        }

        return false;
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'user_children', 'user_id', 'child_id')
            ->where('role', 'child');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'user_children', 'child_id', 'user_id')
            ->where('role', '<>', 'child');
    }

    public function borrows()
    {
        return $this->hasMany(Item::class, 'borrow_user_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function proxy_histories()
    {
        return $this->hasMany(History::class, 'parent_user_id');
    }

    public function getSelfOrChildToBorrow(User $borrow_user)
    {
        return $this->children
            ->prepend($this)
            ->filter(function ($user) use ($borrow_user) {
                return $user->id === $borrow_user->id;
            })
            ->filter()
            ->first();
    }

    public function updateChildren($children)
    {
        $childrenIds = $this->whereIn('id', $children)
            ->where('id', '<>', $this->id)
            ->where('role', 'child')
            ->get()
            ->map(function ($child) {
                return $child->id;
            });

        $this->parents()->sync([]);
        $this->children()->sync($childrenIds);

        return $this;
    }

    public function isExistsTo($user = null): bool
    {
        if (!$user || $this->role !== 'child') {
            return false;
        }

        return $user->children->where('id', $this->id)->isNotEmpty();
    }
}
