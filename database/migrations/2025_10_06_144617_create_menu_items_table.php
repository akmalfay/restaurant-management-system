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
    Schema::create('menu_items', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('name')->unique();
      $table->string('image');
      $table->decimal('price', 10, 2);
      $table->boolean('is_available')->default(true);
      $table
        ->foreignId('category_id')
        ->constrained('categories')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menu_items');
  }
};
