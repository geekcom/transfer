<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/users'], function () {
    Route::get('/', 'UserController@all')->name('allUsers');
    Route::get('{user_id}', 'UserController@show')->name('showUsers');
    Route::post('/transfer', 'UserController@transfer')->name('transfer');
});
