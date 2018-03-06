<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cihaz extends Model
{
    protected $guarded=[];

    protected $fillable = [
        'adi', 'marka', 'model','serino','barkod','sayac','aciklama','cari_id','cariAdi'
    ];
}
