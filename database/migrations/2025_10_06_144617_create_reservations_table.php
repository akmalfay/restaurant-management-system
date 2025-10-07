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
      $table->timestamp('reservation_time');
      $table->integer('session');
      $table->integer('guests');
      $table->enum('status', [
        'confirmed',
        'completed',
        'cancelled',
        'no_show',
      ]);
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
