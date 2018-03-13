<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CihazGaranti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cihazs', function (Blueprint $table) {
            $table->string('garanti',1)->nullable();
            $table->date('garantiTarih')->nullable();
            $table->string('garantiFatura',50)->nullable();
            $table->string('garantiSure',3)->nullable();
            $table->integer('fiyatAlis')->nullable();
            $table->integer('fiyatSatis')->nullable();
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
