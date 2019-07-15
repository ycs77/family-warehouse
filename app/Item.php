<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Item extends Model
{
    use SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category_id', 'borrow_user_id',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'name' => 10,
            'description' => 8,
        ],
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrow_user()
    {
        return $this->belongsTo(User::class, 'borrow_user_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
