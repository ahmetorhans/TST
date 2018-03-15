<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Servis extends Model
{

    protected $guarded = ['get_islem','cariAdi','cihazAdi','get_cari','get_cihaz','get_teknisyen','get_durum'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        
    ];

        
    protected $table = 'servis';

    public function getCari()
    {
        return $this->hasOne('App\Cari','id','cari_id');
    }
    public function getCihaz()
    {
        return $this->hasOne('App\Cihaz','id','cihaz_id');
    }
    //işlem kayıtları
    public function getIslem(){
        return $this->hasMany('App\Islem','servis_id','id')->orderBy('id','DESC');
    }
    //servis durumu
    public function getDurum(){
        return $this->hasOne('App\Durum','value','islemDurumu');
    }
    //teknisyen
    public function getTeknisyen(){
        return $this->hasOne('App\User','id','teknisyen');
    }

   
}
