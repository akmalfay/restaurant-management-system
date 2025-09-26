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
        Schema::create("reservations", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table
                ->foreignId("customer_id")
                ->constrained("customers")
                ->onDelete("cascade");
            $table
                ->foreignId("table_id")
                ->constrained("tables")
                ->onDelete("cascade");
            $table->date("date");
            $table
                ->enum("status", [
                    "confirmed",
                    "cancelled",
                    "completed",
                    "no_show",
                ])
                ->default("confirmed");
            $table->dateTime("started_at");
            $table->dateTime("ended_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reservations");
    }
};
