<?php

/*
* name: locale.auth
*/

//===============  Login
Route::get('/', 'Auth\LoginController@showLoginForm')
->name('.login');

Route::post('login', 'Auth\LoginController@login')
->name('.login.post');

//=============== Logout
Route::post('logout', 'Auth\LoginController@logout')
->name('.logout');

//=============== Register
Route::get('register', 'Auth\RegisterController@showRegistrationForm')
->name('.register');

Route::post('register', 'Auth\RegisterController@register')
->name('.register.post');

//===============
