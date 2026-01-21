<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   * 
   * Mengubah relasi dari order -> reservation (1-to-1) menjadi reservation -> orders (1-to-many)
   * Menghapus order_id dari reservations dan menambahkan reservation_id ke orders.
   */
  public function up(): void
  {
    // 1. Tambah kolom reservation_id ke orders table
    Schema::table('orders', function (Blueprint $table) {
      $table->foreignId('reservation_id')
        ->nullable()
        ->after('customer_id')
        ->constrained('reservations')
        ->onDelete('cascade');
    });

    // 2. Hapus kolom order_id dari reservations table
    Schema::table('reservations', function (Blueprint $table) {
      // Jika ada constraint, drop dulu
      $table->dropForeign(['order_id']);
      $table->dropColumn('order_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Reverse: tambah order_id kembali ke reservations
    Schema::table('reservations', function (Blueprint $table) {
      $table->foreignId('order_id')
        ->after('table_id')
        ->constrained('orders')
        ->onDelete('cascade');
    });

    // Hapus reservation_id dari orders
    Schema::table('orders', function (Blueprint $table) {
      $table->dropForeign(['reservation_id']);
      $table->dropColumn('reservation_id');
    });
  }
};
