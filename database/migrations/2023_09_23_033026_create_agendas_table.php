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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->datetime('time');
            $table->string('name');
            $table->integer('attendant_count');
            $table->string('pic');
            $table->foreignId('room_id')->nullable()->constrained();
            $table->tinyInteger('is_booked')->default(0);
            $table->string('custom_room_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
