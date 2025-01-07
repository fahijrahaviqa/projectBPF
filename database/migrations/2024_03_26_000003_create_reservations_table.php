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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('guest_count');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->string('reservation_code')->unique();
            $table->timestamps();
            $table->softDeletes();

            // Mencegah reservasi ganda pada meja dan waktu yang sama
            $table->unique(['table_id', 'reservation_date', 'start_time'], 'unique_reservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
}; 