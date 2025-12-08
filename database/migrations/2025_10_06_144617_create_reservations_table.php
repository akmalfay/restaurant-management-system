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
      $table->timestamps();
      $table
        ->foreignId('table_id')
        ->constrained('tables')
        ->onDelete('cascade');
      $table
        ->foreignId('order_id')
        ->constrained('orders')
        ->onDelete('cascade');

      // Tanggal reservasi
      $table->date('reservation_date')->index();

      // Status -> three categories only
      $table->enum('status', ['upcoming', 'ongoing', 'finished'])
        ->default('upcoming')
        ->index();

      // Slot per jam
      $table->time('start_time')->default('10:00:00')->after('reservation_date');
      $table->time('end_time')->default('11:00:00')->after('start_time');

      // Prevent duplicates at DB level: satu meja per tanggal+jam
      $table->unique(['table_id', 'reservation_date', 'start_time'], 'reservations_unique_slot');
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
