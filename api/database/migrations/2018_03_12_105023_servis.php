<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Servis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cari_id')->nullable();
            $table->integer('cihaz_id')->nullable();
            $table->string('islemDurumu',50)->nullable();
            $table->string('servisTipi',50)->nullable();
            $table->text('aciklama')->nullable();
            $table->string('odemeTuru',10)->nullable();
            $table->string('fiyat')->nullable();
            $table->string('teknisyen')->nullable();
            $table->text('ekParca')->nullable();
            $table->text('sikayet')->nullable();
            $table->string('bildirim')->nullable();
            
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
