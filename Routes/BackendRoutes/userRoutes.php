<?php

/*
* name: locale.admin.vuser.users
*/

 Route::prefix('users')->name('.users')->group(function () {

    // Index
    Route::get('/', 'Admin\UserController@index')
    ->name('.index')
    ->middleware('can:vuser.users.index');

});
