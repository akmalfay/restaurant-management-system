<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryBatchSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();
    try {
      // Ambil semua inventory yang ada
      $inventories = Inventory::all();

      foreach ($inventories as $inventory) {
        // Tentukan target stock berdasarkan min_stock
        $targetStock = $inventory->min_stock * rand(2, 5); // 2-5x dari min stock

        // Buat 3 batch dengan proporsi berbeda
        $batches = [
          [
            'percentage' => 0.4,
            'days_ago' => 30,
            'expires_days' => 5,
            'cost_multiplier' => 1.0,
          ],
          [
            'percentage' => 0.35,
            'days_ago' => 20,
            'expires_days' => 15,
            'cost_multiplier' => 0.95,
          ],
          [
            'percentage' => 0.25,
            'days_ago' => 10,
            'expires_days' => 30,
            'cost_multiplier' => 0.98,
          ],
        ];

        foreach ($batches as $index => $batchData) {
          $quantity = $targetStock * $batchData['percentage'];
          $createdAt = now()->subDays($batchData['days_ago']);

          // Buat batch
          $batch = InventoryBatch::create([
            'inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'expires_at' => now()->addDays($batchData['expires_days']),
            'cost_per_unit' => $inventory->cost_per_unit * $batchData['cost_multiplier'],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
          ]);

          // Buat stock movement untuk purchase
          StockMovement::create([
            'inventory_id' => $inventory->id,
            'batch_id' => $batch->id,
            'type' => 'purchase',
            'quantity' => $quantity,
            'notes' => 'Pembelian awal batch #' . ($index + 1),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
          ]);
        }

        // Update total stock di inventory
        $totalStock = $inventory->batches()->sum('quantity');
        $inventory->update([
          'stock' => $totalStock,
          'expires_at' => $inventory->batches()->orderBy('expires_at')->first()?->expires_at,
        ]);
      }

      // Buat beberapa batch yang sudah expired dengan movement
      $expiredItems = Inventory::whereIn('name', ['Susu Segar', 'Krim Kental', 'Daging Sapi'])->get();
      foreach ($expiredItems as $item) {
        $quantity = 5.5;
        $createdAt = now()->subDays(45);

        $batch = InventoryBatch::create([
          'inventory_id' => $item->id,
          'quantity' => $quantity,
          'expires_at' => now()->subDays(3),
          'cost_per_unit' => $item->cost_per_unit,
          'created_at' => $createdAt,
          'updated_at' => $createdAt,
        ]);

        // Movement untuk batch expired
        StockMovement::create([
          'inventory_id' => $item->id,
          'batch_id' => $batch->id,
          'type' => 'purchase',
          'quantity' => $quantity,
          'notes' => 'Batch expired - untuk testing',
          'created_at' => $createdAt,
          'updated_at' => $createdAt,
        ]);

        $this->command->warn("Created EXPIRED batch for: {$item->name}");
      }

      DB::commit();
      $this->command->info("✓ Successfully created inventory batches with movements!");
    } catch (\Exception $e) {
      DB::rollBack();
      $this->command->error("Seeding failed: " . $e->getMessage());
      throw $e;
    }
  }
}
