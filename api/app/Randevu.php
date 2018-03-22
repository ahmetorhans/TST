<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Randevu extends Model
{
    protected $table = 'randevu';
    protected $guarded = ['cariAdi','cari'];
    public function cari(){
        return $this->hasone('App\Cari','id','cari_id');
    }
}
