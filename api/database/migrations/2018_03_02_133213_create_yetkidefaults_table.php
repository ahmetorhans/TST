<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYetkidefaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yetkidefaults', function (Blueprint $table) {
            $table->increments('id');
           
            $table->string('bolum');
            $table->string('bolumAdi');
            $table->boolean('giris');
            $table->boolean('yeni');
            $table->boolean('duzelt');
            $table->boolean('sil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yetkidefaults');
    }
}
