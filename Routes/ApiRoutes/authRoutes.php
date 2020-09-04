<?php

use Illuminate\Http\Request;


Route::prefix('/auth')->name('.auth')->group(function(){

    Route::post('login', 'Api\Auth\LoginApiController@login')
    ->name('.login');

    Route::post('logout', 'Api\Auth\LoginApiController@logout')
    ->name('.logout')
    ->middleware('auth:api');

    Route::post('register', 'Api\Auth\RegisterApiController@register')
    ->name('.register');

});
