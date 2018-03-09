<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caris', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adi',100)->nullable();
            $table->text('adres')->nullable();
            $table->string('telefon',20)->nullable();
            $table->string('yetkili',50)->nullable();
            $table->string('vergiNo',50)->nullable();
            $table->string('vergiDairesi',50)->nullable();
            $table->text('aciklama')->nullable();
            $table->boolean('durum')->nullable();
            $table->string('eposta',100)->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('login')->nullable();
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
        Schema::dropIfExists('caris');
    }
}
