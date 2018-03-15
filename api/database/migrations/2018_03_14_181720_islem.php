<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Islem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('islem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('servis_id');
            $table->string('adi',50)->nullable();
            $table->date('tarih')->nullable();
            $table->text('aciklama')->nullable();
            $table->string('user')->nullable();

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
        Schema::dropIfExists('islem');
    }
}
