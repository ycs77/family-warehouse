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
    Route::get('categories/{category}', 'CategoryController@show')->name('category');
    Route::resource('categories', 'CategoryController')->except('show');
});

Auth::routes([
    'register' => false,
    'reset' => false,
]);
