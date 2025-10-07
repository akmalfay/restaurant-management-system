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
    Schema::create('staff_details', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table
        ->foreignId('user_id')
        ->constrained('users')
        ->onDelete('cascade');
      $table->boolean('is_active')->default('true');
      $table->enum('role', ['chef', 'waiter', 'cashier']);
      $table->timestamp('joined_at');
      $table->timestamp('left_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('staff_details');
  }
};
