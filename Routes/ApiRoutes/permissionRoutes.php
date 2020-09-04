<?php

use Illuminate\Http\Request;

Route::prefix('/permissions')->name('.permissions')->middleware('auth:api')->group(function () {

    Route::get('/', 'Api\PermissionApiController@index')
        ->name('.index')
        ->middleware('can:user.permissions.index');

    Route::get('/{criteria}', 'Api\PermissionApiController@show')
        ->name('.show')
        ->middleware('can:user.permissions.index');

    Route::post('/', 'Api\PermissionApiController@create')
        ->name('.create')
        ->middleware('can:user.permissions.create');

    Route::put('/{criteria}', 'Api\PermissionApiController@update')
        ->name('.update')
        ->middleware('can:user.permissions.update');

    Route::delete('/{criteria}', 'Api\PermissionApiController@delete')
        ->name('.delete')
        ->middleware('can:user.permissions.delete');

    Route::post('/assign', 'Api\PermissionApiController@assign')
        ->name('.assign')
        ->middleware('can:user.permissions.assign');

    Route::post('/revoke', 'Api\PermissionApiController@revoke')
        ->name('.revoke')
        ->middleware('can:user.permissions.revoke');

});
