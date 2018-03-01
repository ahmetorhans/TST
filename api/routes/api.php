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
Route::post('upload', 'HomeController@upload');
Route::post('auth/login', 'Authentication@login');
Route::get('userList', 'Authentication@userList');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'HomeController@index');
    Route::post('register', 'Authentication@register');
});
