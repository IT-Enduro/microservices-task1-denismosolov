<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('film_session')->insert([
            'id' => '1',
            'session_uid' => '5fe6a3cb-b04a-4b68-af3e-65e34141f761',
            'film_uid' => '049161bb-badd-4fa8-9d90-87c9a82b0668',
            'total_seats' => '5000',
            'booked_seats' => '0',
            'date' => '2024-01-01T08:00:00',
            'cinema_id' => '1',
        ]);
    }
}
