<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("schedules", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table
                ->foreignId("staff_id")
                ->constrained("staffs")
                ->onDelete("cascade");
            $table->date("date");
            $table->enum("shift", ["morning", "night"]);
            // morning -> 08:00 - 16:00
            // night -> 16:00 -> 24:00
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("schedules");
    }
};
