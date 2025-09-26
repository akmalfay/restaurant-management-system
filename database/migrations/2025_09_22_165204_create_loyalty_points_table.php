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
        Schema::create("loyalty_points", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("customer_id")
                ->constrained("customers")
                ->onDelete("cascade");
            $table
                ->foreignId("order_id")
                ->nullable()
                ->constrained("orders")
                ->onDelete("cascade");
            $table->integer("points")->default(0);
            $table->enum("type", ["earned", "redeemed", "expired", "bonus"]);
            $table->date("expires_at");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("loyalty_points");
    }
};
