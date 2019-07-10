<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category_id', 'borrow_user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrow_user()
    {
        return $this->belongsTo(User::class, 'borrow_user_id');
    }
}
