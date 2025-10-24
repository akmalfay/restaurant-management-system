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
    Schema::create('stock_movements', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table
        ->foreignId('inventory_id')
        ->constrained('inventories')
        ->onDelete('cascade');
      $table->enum('type', ['purchase', 'usage', 'waste', 'adjustment']);
      $table->decimal('quantity', 10, 3);
      $table->text('notes')->nullable(); // TAMBAHKAN INI
      $table->unsignedBigInteger('batch_id')->nullable();
      $table->index(['inventory_id']);
      $table->index(['batch_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stock_movements');
  }
};
