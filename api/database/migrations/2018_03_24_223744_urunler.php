<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Urunler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urunler', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('stok_kodu',30)->nullable();
            $table->string('aciklama')->nullable();
            $table->string('bayiFiyat',12)->nullable();
            $table->string('fiyat',12)->nullable();
            $table->string('marka',50)->nullable();
            $table->string('model',50)->nullable();
            $table->text('ekBilgi')->nullable();
              
            $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
