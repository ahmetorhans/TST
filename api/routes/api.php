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
   
    Route::get('userListele', 'Users@userListele');
    Route::get('userSil', 'Users@userSil');
    Route::post('userKaydet', 'Users@userKaydet');
    Route::get('teknisyenListele', 'Users@teknisyenListele');
    Route::get('yetkiler', 'Users@yetkiler');
    Route::get('yetkiDefault', 'Users@yetkiDefault');
    Route::get('profilGetir','Users@profilGetir');
    Route::post('profilKaydet','Users@profilKaydet');
    Route::get('guard', 'Users@guard');
    
    Route::post('upload', 'Upload@uploadFile');

    Route::get('cariListele', 'Cari@cariListele');
    Route::get('cariGetir/{id}', 'Cari@cariGetir');
    Route::post('cariKaydet', 'Cari@cariKaydet');
    Route::get('cariSil', 'Cari@cariSil');
    Route::get('listShortCari', 'Cari@listShortCari');

    Route::get('cihazListele', 'Cihaz@cihazListele');
    Route::post('cihazKaydet', 'Cihaz@cihazKaydet');
    Route::get('cihazSil', 'Cihaz@cihazSil');
    Route::get('cihazGetir/{id}', 'Cihaz@cihazGetir');
    Route::get('listShortCihaz', 'Cihaz@listShortCihaz');
    Route::get('listShortCihazId/{id}', 'Cihaz@listShortCihazId');

    Route::get('servisListele', 'Servis@servisListele');
    Route::post('servisKaydet', 'Servis@servisKaydet');
    Route::get('servisSil', 'Servis@servisSil');
    Route::get('servisGetir/{id}', 'Servis@servisGetir');
    Route::get('servisInit', 'Servis@servisInit');
    Route::get('islemDurumlariListele', 'Servis@islemDurumlariListele');
    Route::post('islemKaydet', 'Servis@islemKaydet');
    Route::get('islemSil/{id}', 'Servis@islemSil');
    Route::get('servisListeleBekleyen', 'Servis@servisListeleBekleyen');

    Route::get('randevuListele', 'Randevu@randevuListele');
    Route::post('randevuKaydet', 'Randevu@randevuKaydet');
    Route::get('randevuSil', 'Randevu@randevuSil');
    Route::get('randevuGetir/{id}', 'Randevu@randevuGetir');
    Route::get('listShortRandevu', 'Randevu@listShortRandevu');
    Route::get('listShortRandevuId/{id}', 'Randevu@listShortRandevuId');
    


    
});
