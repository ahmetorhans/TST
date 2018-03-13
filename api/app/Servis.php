<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{

    protected $guarded = [];

    protected $table = 'servis';

    public function getCari()
    {
        return $this->hasMany('App\Cari','id','cari_id');
    }
    public function getCihaz()
    {
        return $this->hasMany('App\Cihaz','id','cihaz_id');
    }

    public function getServis()
    {
        return $this->getCari()->union($this->getCihaz()->toBase());
    }
}
