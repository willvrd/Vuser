<?php

use Illuminate\Http\Request;

/*
* name: locale.api.user.roles
*/

Route::prefix('/roles')->name('.roles')->middleware('auth:api')->group(function(){

    Route::get('/', 'Api\RoleApiController@index')
    ->name('.index')
    ->middleware('can:user.roles.index');

    Route::get('/{criteria}', 'Api\RoleApiController@show')
    ->name('.show')
    ->middleware('can:user.roles.index');

    Route::post('/', 'Api\RoleApiController@create')
    ->name('.create')
    ->middleware('can:user.roles.create');

    Route::put('/{criteria}', 'Api\RoleApiController@update')
    ->name('.update')
    ->middleware('can:user.roles.update');

    Route::delete('/{criteria}', 'Api\RoleApiController@delete')
    ->name('.delete')
    ->middleware('can:user.roles.delete');

    Route::post('/assign', 'Api\RoleApiController@assign')
    ->name('.assign')
    ->middleware('can:user.roles.assign');

    Route::post('/unassign', 'Api\RoleApiController@unassign')
    ->name('.unassign')
    ->middleware('can:user.roles.unassign');


});
