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
      // Tanggal reservasi (untuk unique slot per hari + shift)
      $table->date('reservation_date')->index();

      // Tambah pending agar bisa di-ACC admin/cashier
      $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])
        ->default('pending')
        ->index();

      // Tambah kolom slot per jam
      $table->time('start_time')->default('10:00:00')->after('reservation_date');
      $table->time('end_time')->default('11:00:00')->after('start_time');
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
