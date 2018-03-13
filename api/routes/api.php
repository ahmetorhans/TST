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
Route::get('test', 'HomeController@test');


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('userList', 'Users@listUser');
    Route::get('listeleTeknisyen', 'Users@listeleTeknisyen');
    Route::post('register', 'Users@newUser');
    Route::get('yetkiler', 'Users@yetki');

    Route::get('yetkiDefault', 'Users@yetkiDefault');
    Route::get('deleteUser', 'Users@deleteUser');
    
    Route::post('upload', 'Upload@uploadFile');

    Route::get('listCari', 'Cari@listCari');
    Route::get('listShortCari', 'Cari@listShortCari');
    Route::get('getCari/{id}', 'Cari@getCari');
    Route::post('newCari', 'Cari@newCari');
    Route::get('deleteCari', 'Cari@deleteCari');

    Route::get('listCihaz', 'Cihaz@listCihaz');
    Route::post('newCihaz', 'Cihaz@newCihaz');
    Route::get('deleteCihaz', 'Cihaz@deleteCihaz');
    Route::get('getCihaz/{id}', 'Cihaz@getCihaz');
    Route::get('listShortCihaz', 'Cihaz@listShortCihaz');
    Route::get('listShortCihazId/{id}', 'Cihaz@listShortCihazId');

    Route::get('listServis', 'Servis@listeServis');
    Route::post('newServis', 'Servis@yeniServis');
    Route::get('deleteServis', 'Servis@silServis');
    Route::get('getServis/{id}', 'Servis@getirServis');
    


    
});
