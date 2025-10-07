<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $listStatus = ['available', 'occupied', 'reserved', 'maintenance'];

    $categories = ['Indoor', 'Outdoor', 'Terrace', 'VIP'];

    $tables = [];

    foreach ($categories as $prefix) {
      for ($i = 1; $i <= 10; $i++) {
        $tables[] = [
          'name' => $prefix . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
          'status' => $listStatus[fake()->numberBetween(0, 3)],
        ];
      }
    }

    Table::insert($tables);
  }
}
