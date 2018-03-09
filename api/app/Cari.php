<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cari extends Model
{
     protected $guarded=[];

     protected $fillable = [
        'adi', 'adres', 'telefon','vergiNo','vergiDairesi','durum','yetkili','aciklama','user_id','eposta','turu'
    ];
}
