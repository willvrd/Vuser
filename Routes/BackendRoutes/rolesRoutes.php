<?php

/*
* name: locale.admin.vuser.roles
*/

 Route::prefix('roles')->name('.roles')->group(function () {

    // Index
    Route::get('/', 'Admin\RoleController@index')
    ->name('.index')
    ->middleware('can:vuser.roles.index');

});
