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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table
        ->foreignId('customer_id')
        ->nullable()
        ->constrained('customer_details')
        ->onDelete('cascade');
      $table->enum('type', ['dine_in', 'takeway', 'delivery']);
      $table->decimal('total', 12, 2);
      $table
        ->enum('status', [
          'pending',
          'preparing',
          'ready',
          'served',
          'completed',
        ])
        ->default('pending');
      $table->integer('points_redeemed')->nullable();
      $table->timestamp('order_time');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
