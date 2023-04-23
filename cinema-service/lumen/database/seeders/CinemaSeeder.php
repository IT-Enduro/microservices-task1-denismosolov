<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cinema')->insert([
            'id' => '1',
            'cinema_uid' => '06cc4ba3-ee97-4d29-a814-c40588290d17',
            'name' => 'Кинотеатр Москва',
            'address' => 'Ереван, улица Хачатура Абовяна, 18',
        ]);
    }
}
