<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       
            DB::table('caris')->insert([
                'adi' => str_random(10),
                'adres' => str_random(10),
                'yetkili' => str_random(10),
            ]);
        

    }
}
