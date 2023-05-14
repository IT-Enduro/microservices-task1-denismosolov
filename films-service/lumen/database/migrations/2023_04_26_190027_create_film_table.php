<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('film', function (Blueprint $table) {
            $table->id();
            $table->uuid('film_uid');
            $table->string('name');
            $table->decimal('rating', 8, 2)->default(10);
            $table->string('director')->nullable();
            $table->string('producer')->nullable();
            $table->string('genre');

            // $table->check(DB::raw('rating between 0 and 10')); // CHECK ( rating BETWEEN 0 AND 10 )
            $table->unique('film_uid', 'udx_film_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film');
    }
};
