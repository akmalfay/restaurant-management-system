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
        Schema::create("recipe_ingredients", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table
                ->foreignId("menu_id")
                ->constrained("menu_items")
                ->onDelete("cascade");
            $table
                ->foreignId("inventory_id")
                ->constrained("inventories")
                ->onDelete("cascade");
            $table->decimal("quantity", 10, 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("recipe_ingredients");
    }
};
