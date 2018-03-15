<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Servis extends Model
{

    protected $guarded = ['get_islem','cariAdi','cihazAdi','get_cari','get_cihaz'];

        
    protected $table = 'servis';

    public function getCari()
    {
        return $this->hasOne('App\Cari','id','cari_id');
    }
    public function getCihaz()
    {
        return $this->hasOne('App\Cihaz','id','cihaz_id');
    }

    public function getIslem(){
        return $this->hasMany('App\Islem','servis_id','id');
    }

   
}
