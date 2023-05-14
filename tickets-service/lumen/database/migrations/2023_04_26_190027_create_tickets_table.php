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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_uid');
            $table->uuid('film_uid');
            $table->uuid('session_uid');
            $table->string('user_name', 80);
            $table->timestamp('date');
            $table->enum('status', ['BOOKED', 'CANCELED']);

            $table->unique('ticket_uid', 'udx_tickets_ticket_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
