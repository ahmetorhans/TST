<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->text('photo')->nullable();
                $table->string('token')->nullable();
                $table->string('role',10)->nullable();
                $table->boolean('musteri');
                $table->rememberToken();
                $table->timestamps();
            });
            DB::table('users')->insert(array('name'=>'Admin','email'=>'admin@admin.com', 'password'=>bcrypt('admin'),'role'=>'super','musteri'=>'0'));

        }
        
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
