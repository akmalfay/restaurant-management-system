<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = [
      'VIP' => ['count' => 10, 'capacity' => 4],
      'Terrace' => ['count' => 10, 'capacity' => 4],
      'Outdoor' => ['count' => 10, 'capacity' => 3],
      'Indoor' => ['count' => 10, 'capacity' => 2],
    ];

    $tables = [];

    // Status distribution: available 70%, reserved 15%, occupied 10%, maintenance 5%
    $weightedStatus = function () {
      $r = random_int(1, 100);
      if ($r <= 70) return 'available';
      if ($r <= 85) return 'reserved';
      if ($r <= 95) return 'occupied';
      return 'maintenance';
    };

    foreach ($categories as $prefix => $config) {
      for ($i = 1; $i <= $config['count']; $i++) {
        $tables[] = [
          'name' => $prefix . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
          'capacity' => $config['capacity'],
          'status' => $weightedStatus(),
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }
    }

    Table::truncate();
    Table::insert($tables);

    $this->command->info('✓ TableSeeder: 40 meja dibuat');
    $this->command->info('  • VIP: 10 meja (4 kursi)');
    $this->command->info('  • Terrace: 10 meja (4 kursi)');
    $this->command->info('  • Outdoor: 10 meja (3 kursi)');
    $this->command->info('  • Indoor: 10 meja (2 kursi)');
  }
}
