<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Main
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('history/borrow', 'HomeController@borrow_history')->name('history.borrow');
    Route::get('history/proxy', 'HomeController@proxy_borrow_history')->name('history.proxy');

    // User
    Route::resource('users', 'UserController');
    Route::get('users/{user}/password', 'UserController@password')->name('users.password');
    Route::put('users/{user}/password', 'UserController@updatePassword')->name('users.password.update');
    Route::get('users/{user}/history/borrow', 'UserController@borrow_history')->name('users.history.borrow');
    Route::get('users/{user}/history/proxy', 'UserController@proxy_borrow_history')->name('users.history.proxy');

    // Category
    Route::resource('categories', 'CategoryController');

    // Items
    Route::resource('items', 'ItemController');

    // Item borrow
    Route::get('items/{item}/qrcode', 'ItemBorrowController@qrcode')->name('items.qrcode');
    Route::get('items/{item}/borrow', 'ItemBorrowController@borrowPage')->name('items.borrow.page');
    Route::post('items/{item}/borrow/{user}', 'ItemBorrowController@borrow')->name('items.borrow');
    Route::get('items/{item}/return', 'ItemBorrowController@returnPage')->name('items.return.page');
    Route::post('items/{item}/return', 'ItemBorrowController@return')->name('items.return');

    // Scanner
    Route::get('scanner', 'ScannerController@index')->name('scanner.index');
    Route::post('scanner/decode', 'ScannerController@decode')->name('scanner.decode');
    Route::get('scanner/error', 'ScannerController@error')->name('scanner.error');
});

Auth::routes([
    'register' => false,
    'reset' => false,
]);
