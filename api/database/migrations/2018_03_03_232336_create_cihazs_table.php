<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCihazsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cihazs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cari_id');
            $table->string('adi')->nullable();
            $table->string('marka')->nullable();
            $table->string('model')->nullable();
            $table->string('serino')->nullable();
            $table->text('barkod')->nullable();
            $table->string('sayac')->nullable();

           
            $table->text('aciklama');
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
        Schema::dropIfExists('cihazs');
    }
}
