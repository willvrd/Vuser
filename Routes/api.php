<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$locale = \App::getLocale();

/*
* name: locale.api.user
*/

Route::prefix('/user/v1')->name($locale.'.api.user')->group(function(){

    //======  USERS
    require('ApiRoutes/userRoutes.php');

    //======  AUTH
    require('ApiRoutes/authRoutes.php');

    //======  ROLES
    require('ApiRoutes/roleRoutes.php');

    //======  PERMISSIONS
    require('ApiRoutes/permissionRoutes.php');

});
