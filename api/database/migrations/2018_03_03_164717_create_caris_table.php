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
            $table->string('adi',100);
            $table->text('adres');
            $table->string('telefon',20);
            $table->string('yetkili',50);
            $table->string('vergiNo',50);
            $table->string('vergiDairesi',50);
            $table->text('aciklama');
            $table->boolean('durum');
            $table->integer('user_id');
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
