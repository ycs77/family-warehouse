<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', 'HomeController@index')->name('home');

Auth::routes([
    'register' => false,
    'reset' => false,
]);
