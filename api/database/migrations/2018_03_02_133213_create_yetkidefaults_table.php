<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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

        DB::table('yetkidefaults')->insert(array('bolum'=>'kullanici','bolumAdi'=>'Kullanıcılar'));
        DB::table('yetkidefaults')->insert(array('bolum'=>'cari','bolumAdi'=>'Cariler'));
        DB::table('yetkidefaults')->insert(array('bolum'=>'rapor','bolumAdi'=>'Raporlar'));
        DB::table('yetkidefaults')->insert(array('bolum'=>'cihaz','bolumAdi'=>'Cihazlar'));
        DB::table('yetkidefaults')->insert(array('bolum'=>'servis','bolumAdi'=>'Servisler'));
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
