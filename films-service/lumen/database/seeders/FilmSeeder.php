<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('film')->insert([
            'id' => '1',
            'film_uid' => '049161bb-badd-4fa8-9d90-87c9a82b0668',
            'name' => 'Terminator 2 Judgment day',
            'rating' => '8.6',
            'director' => 'James Cameron',
            'producer' => 'James Cameron',
            'genre' => 'Sci-Fi',
        ]);
    }
}
