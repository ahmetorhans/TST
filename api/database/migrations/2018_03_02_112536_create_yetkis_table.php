<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYetkisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yetkis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('bolum');
            $table->string('bolumAdi');
            $table->boolean('giris');
            $table->boolean('yeni');
            $table->boolean('duzelt');
            $table->boolean('sil');
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
        Schema::dropIfExists('yetkis');
    }
}
