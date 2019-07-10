<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Main
    Route::get('/', 'HomeController@index')->name('home');

    // User
    Route::get('users/{user}/password', 'UserController@password')->name('users.password');
    Route::put('users/{user}/password', 'UserController@updatePassword')->name('users.password.update');
    Route::resource('users', 'UserController');

    // Category
    Route::get('categories/create', 'CategoryController@create')->name('categories.create');
    Route::get('categories/{category}', 'CategoryController@show')->name('category');
    Route::resource('categories', 'CategoryController')->except('show');

    // Items
    Route::get('items/create', 'ItemController@create')->name('items.create');
    Route::get('items/{item}', 'ItemController@show')->name('item');
    Route::resource('items', 'ItemController')->except('show');
    Route::get('items/{item}/borrow', 'ItemController@borrowPage')->name('items.borrow.page');
    Route::post('items/{item}/borrow/{user?}', 'ItemController@borrow')->name('items.borrow');
    Route::post('items/{item}/return', 'ItemController@return')->name('items.return');
});

Auth::routes([
    'register' => false,
    'reset' => false,
]);
