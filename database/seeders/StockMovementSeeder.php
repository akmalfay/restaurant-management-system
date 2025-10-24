<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Inventory;
use App\Models\InventoryBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockMovementSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();
    try {
      // Ambil beberapa inventory untuk simulasi penggunaan
      $items = [
        'Daging Sapi' => [
          ['type' => 'usage', 'qty' => 8.5, 'days_ago' => 25],
          ['type' => 'usage', 'qty' => 12.75, 'days_ago' => 20],
          ['type' => 'usage', 'qty' => 5.25, 'days_ago' => 15],
        ],
        'Susu Segar' => [
          ['type' => 'usage', 'qty' => 15.5, 'days_ago' => 22],
          ['type' => 'usage', 'qty' => 10.0, 'days_ago' => 18],
          ['type' => 'waste', 'qty' => 2.5, 'days_ago' => 15],
          ['type' => 'usage', 'qty' => 8.0, 'days_ago' => 10],
        ],
        'Tepung Terigu' => [
          ['type' => 'usage', 'qty' => 45.0, 'days_ago' => 18],
          ['type' => 'usage', 'qty' => 30.0, 'days_ago' => 12],
          ['type' => 'adjustment', 'qty' => 5.0, 'days_ago' => 10],
          ['type' => 'usage', 'qty' => 20.0, 'days_ago' => 5],
        ],
        'Tomat' => [
          ['type' => 'usage', 'qty' => 8.5, 'days_ago' => 8],
          ['type' => 'usage', 'qty' => 4.5, 'days_ago' => 3],
        ],
        'Daging Ayam' => [
          ['type' => 'usage', 'qty' => 12.0, 'days_ago' => 15],
          ['type' => 'usage', 'qty' => 8.5, 'days_ago' => 10],
          ['type' => 'usage', 'qty' => 6.0, 'days_ago' => 5],
        ],
        'Kentang' => [
          ['type' => 'usage', 'qty' => 25.0, 'days_ago' => 12],
          ['type' => 'usage', 'qty' => 18.0, 'days_ago' => 6],
        ],
      ];

      foreach ($items as $itemName => $movements) {
        $inventory = Inventory::where('name', $itemName)->first();

        if (!$inventory) continue;

        foreach ($movements as $movement) {
          // Ambil batch tertua yang masih ada stock (FIFO)
          $batch = $inventory->batches()
            ->where('quantity', '>', 0)
            ->orderBy('expires_at', 'asc')
            ->first();

          if (!$batch) continue;

          $createdAt = now()->subDays($movement['days_ago']);
          $quantity = $movement['qty'];

          // Kurangi quantity di batch
          if ($batch->quantity >= $quantity) {
            $batch->decrement('quantity', $quantity);
          } else {
            $quantity = $batch->quantity;
            $batch->update(['quantity' => 0]);
          }

          // Buat movement record
          StockMovement::create([
            'inventory_id' => $inventory->id,
            'batch_id' => $batch->id,
            'type' => $movement['type'],
            'quantity' => -$quantity, // negative untuk pengurangan
            'notes' => $this->generateNotes($movement['type']),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
          ]);

          // Update stock di inventory
          $inventory->decrement('stock', $quantity);
        }

        // Update expires_at ke batch terdekat yang masih ada
        $nearestBatch = $inventory->batches()
          ->where('quantity', '>', 0)
          ->orderBy('expires_at', 'asc')
          ->first();

        if ($nearestBatch) {
          $inventory->update(['expires_at' => $nearestBatch->expires_at]);
        }
      }

      DB::commit();
      $this->command->info('✓ Created stock movements successfully!');
    } catch (\Exception $e) {
      DB::rollBack();
      $this->command->error("Seeding failed: " . $e->getMessage());
      throw $e;
    }
  }

  private function generateNotes(string $type): string
  {
    $notes = [
      'usage' => [
        'Digunakan untuk produksi menu',
        'Pemakaian dapur',
        'Penggunaan untuk pesanan pelanggan',
        'Konsumsi produksi harian',
      ],
      'waste' => [
        'Bahan rusak/kadaluarsa',
        'Terbuang karena expired',
        'Quality control - tidak layak',
        'Kerusakan saat penyimpanan',
      ],
      'adjustment' => [
        'Stock opname adjustment',
        'Koreksi inventory',
        'Penyesuaian stock fisik',
        'Adjustment setelah pengecekan',
      ],
    ];

    return $notes[$type][array_rand($notes[$type])];
  }
}
