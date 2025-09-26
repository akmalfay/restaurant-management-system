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
        Schema::create("inventories", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name")->unique();
            $table->string("item_code")->unique()->nullable();
            $table->decimal("cost_per_unit", 12, 2)->nullable();
            $table->decimal("stock", 10, 3)->default(0);
            $table->decimal("min_stock", 10, 3)->default(0);
            $table->enum("unit", ["kg", "liter", "pcs", "pack", "bottle"]);
            $table->date("expires_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("inventories");
    }
};
