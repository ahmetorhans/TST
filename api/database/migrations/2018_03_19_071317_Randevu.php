<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Randevu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('randevu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cari_id')->nullable();
            $table->string('islemDurumu',50)->nullable();
            $table->text('yetkili')->nullable();
            $table->text('aciklama')->nullable();
            $table->string('kullanici')->nullable();
            $table->text('sikayet')->nullable();
            $table->string('bildirim')->nullable();
            $table->date('islemTarihi')->nullable();
            $table->date('randevuTarihi')->nullable();
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
