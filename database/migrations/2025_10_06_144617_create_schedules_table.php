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
    Schema::create('schedules', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table
        ->foreignId('staff_id')
        ->constrained('staff_details')
        ->onDelete('cascade');
      $table->date('schedule_date');
      $table->enum('shift', ['morning', 'night']);
      $table->index(['schedule_date', 'shift']); // tambah index utk join/lookup cepat
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('schedules');
  }
};
