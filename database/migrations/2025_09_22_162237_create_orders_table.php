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
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("table_id")
                ->nullable()
                ->constrained("tables")
                ->onDelete("cascade");
            $table
                ->foreignId("staff_id")
                ->nullable()
                ->constrained("staffs")
                ->onDelete("cascade");
            $table
                ->foreignId("customer_id")
                ->constrained("customers")
                ->onDelete("cascade");
            $table->enum("type", ["dine_in", "takeway", "delivery"]);
            $table->decimal("total", 12, 2);
            $table
                ->enum("status", [
                    "pending",
                    "preparing",
                    "ready",
                    "served",
                    "completed",
                ])
                ->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
