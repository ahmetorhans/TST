<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{

    protected $guarded = ['cariAdi','cihazAdi','get_cari','get_cihaz','get_teknisyen'];

    
    protected $table = 'servis';

    public function getCari()
    {
        return $this->hasOne('App\Cari','id','cari_id');
    }
    public function getCihaz()
    {
        return $this->hasOne('App\Cihaz','id','cihaz_id');
    }

   

   
}
