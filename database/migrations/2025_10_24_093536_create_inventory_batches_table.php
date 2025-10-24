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
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->decimal('quantity', 12, 3)->default(0);
            $table->date('expires_at')->nullable();
            $table->decimal('cost_per_unit', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            // pastikan kolom ada (untuk berjaga di lingkungan CI)
            if (!Schema::hasColumn('stock_movements', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('inventory_id');
            }
            $table->foreign('batch_id')
                ->references('id')->on('inventory_batches')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (Schema::hasColumn('stock_movements', 'batch_id')) {
                $table->dropForeign(['batch_id']);
                $table->dropColumn('batch_id');
            }
        });
        Schema::dropIfExists('inventory_batches');
    }
};
