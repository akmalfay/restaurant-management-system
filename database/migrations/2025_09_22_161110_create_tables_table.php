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
        Schema::create("tables", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("number")->unique();
            $table->integer("capacity")->default(2);
            $table
                ->enum("status", [
                    "available",
                    "occupied",
                    "reserved",
                    "maintenance",
                ])
                ->default("available");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tables");
    }
};
