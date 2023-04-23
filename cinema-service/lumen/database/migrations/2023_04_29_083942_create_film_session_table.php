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
        Schema::create('film_session', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_uid');
            $table->uuid('film_uid');
            $table->integer('total_seats');
            $table->integer('booked_seats')->default(0);
            $table->timestamp('date');
            $table->unsignedBigInteger('cinema_id');

            // $table->checkConstraint('booked_seats <= total_seats');
            $table->foreign('cinema_id')->references('id')->on('cinema');
            $table->unique('session_uid', 'udx_film_session_session_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_session');
    }
};
