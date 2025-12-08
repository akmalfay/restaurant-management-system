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

    // Status distribution: available majority, maintenance small percent
    $weightedStatus = function () {
      $r = random_int(1, 100);
      return $r <= 88 ? 'available' : 'maintenance';
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

    $this->command->info('✓ TableSeeder: meja dibuat dengan kapasitas & status (available/maintenance)');
  }
}
