<?php

/*
* name: locale.admin.vuser.permissions
*/

 Route::prefix('permissions')->name('.permissions')->group(function () {

    // Index
    Route::get('/', 'Admin\PermissionController@index')
    ->name('.index')
    ->middleware('can:vuser.permissions.index');

});
