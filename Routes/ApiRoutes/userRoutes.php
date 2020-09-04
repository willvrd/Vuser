<?php

use Illuminate\Http\Request;

Route::prefix('/users')->name('.users')->middleware('auth:api')->group(function(){

    Route::get('/', 'Api\UserApiController@index')
    ->name('.index')
    ->middleware('can:user.users.index');

    Route::get('/{criteria}', 'Api\UserApiController@show')
    ->name('.show')
    ->middleware('can:user.users.index');

    Route::post('/', 'Api\UserApiController@create')
    ->name('.create')
    ->middleware('can:user.users.create');

    Route::put('/{criteria}', 'Api\UserApiController@update')
    ->name('.update')
    ->middleware('can:user.users.update');

    Route::delete('/{criteria}', 'Api\UserApiController@delete')
    ->name('.delete')
    ->middleware('can:user.users.delete');

});
