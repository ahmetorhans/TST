<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cihaz extends Model
{
    protected $guarded=['cari'];

    public function cari(){
        return $this->hasone('App\Cari','id','cari_id');
    }
   
}
