<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'icon',
    ];

    /**
     * Check the other category is self or child.
     *
     * @param  \App\Category|null  $category
     * @return boolean
     */
    public function isSelfOrChild($category = null)
    {
        if (!$category) {
            return false;
        }

        return $category->is($this) || $category->isDescendantOf($this);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
