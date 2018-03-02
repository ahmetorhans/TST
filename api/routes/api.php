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

Route::post('auth/login', 'Authentication@login');


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('userList', 'Users@listUser');
    Route::post('register', 'Users@newUser');
    Route::post('upload', 'Upload@uploadFile');
    
});
