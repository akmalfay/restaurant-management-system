<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $inventories = [
      [
        'name' => 'Rice',
        'cost_per_unit' => 12000,
        'stock' => 600,
        'min_stock' => 100,
        'unit' => 'kg',
        'expires_at' => null,
      ],
      [
        'name' => 'Chicken',
        'cost_per_unit' => 35000,
        'stock' => 300,
        'min_stock' => 50,
        'unit' => 'kg',
        'expires_at' => now()->addDays(5),
      ],
      [
        'name' => 'Beef',
        'cost_per_unit' => 95000,
        'stock' => 150,
        'min_stock' => 30,
        'unit' => 'kg',
        'expires_at' => now()->addDays(5),
      ],
      [
        'name' => 'Fish',
        'cost_per_unit' => 45000,
        'stock' => 200,
        'min_stock' => 40,
        'unit' => 'kg',
        'expires_at' => now()->addDays(4),
      ],
      [
        'name' => 'Eggs',
        'cost_per_unit' => 2000,
        'stock' => 2100,
        'min_stock' => 300,
        'unit' => 'pcs',
        'expires_at' => now()->addWeeks(2),
      ],
      [
        'name' => 'Cooking Oil',
        'cost_per_unit' => 25000,
        'stock' => 80,
        'min_stock' => 20,
        'unit' => 'liter',
        'expires_at' => now()->addMonths(6),
      ],
      [
        'name' => 'Soy Sauce',
        'cost_per_unit' => 15000,
        'stock' => 15,
        'min_stock' => 5,
        'unit' => 'bottle',
        'expires_at' => now()->addMonths(12),
      ],
      [
        'name' => 'Sweet Soy Sauce',
        'cost_per_unit' => 18000,
        'stock' => 15,
        'min_stock' => 5,
        'unit' => 'bottle',
        'expires_at' => now()->addMonths(12),
      ],
      [
        'name' => 'Chili',
        'cost_per_unit' => 40000,
        'stock' => 40,
        'min_stock' => 10,
        'unit' => 'kg',
        'expires_at' => now()->addDays(7),
      ],
      [
        'name' => 'Garlic',
        'cost_per_unit' => 30000,
        'stock' => 50,
        'min_stock' => 15,
        'unit' => 'kg',
        'expires_at' => now()->addDays(14),
      ],
      [
        'name' => 'Shallot',
        'cost_per_unit' => 32000,
        'stock' => 50,
        'min_stock' => 15,
        'unit' => 'kg',
        'expires_at' => now()->addDays(14),
      ],
      [
        'name' => 'Cabbage',
        'cost_per_unit' => 10000,
        'stock' => 70,
        'min_stock' => 20,
        'unit' => 'kg',
        'expires_at' => now()->addDays(5),
      ],
      [
        'name' => 'Carrot',
        'cost_per_unit' => 15000,
        'stock' => 50,
        'min_stock' => 15,
        'unit' => 'kg',
        'expires_at' => now()->addDays(10),
      ],
      [
        'name' => 'Potato',
        'cost_per_unit' => 12000,
        'stock' => 60,
        'min_stock' => 20,
        'unit' => 'kg',
        'expires_at' => now()->addDays(14),
      ],
      [
        'name' => 'Tofu',
        'cost_per_unit' => 5000,
        'stock' => 500,
        'min_stock' => 100,
        'unit' => 'pcs',
        'expires_at' => now()->addDays(3),
      ],
      [
        'name' => 'Tempeh',
        'cost_per_unit' => 6000,
        'stock' => 400,
        'min_stock' => 80,
        'unit' => 'pcs',
        'expires_at' => now()->addDays(3),
      ],
      [
        'name' => 'Instant Noodles',
        'cost_per_unit' => 3500,
        'stock' => 300,
        'min_stock' => 50,
        'unit' => 'pack',
        'expires_at' => now()->addMonths(6),
      ],
      [
        'name' => 'Mineral Water',
        'cost_per_unit' => 5000,
        'stock' => 500,
        'min_stock' => 100,
        'unit' => 'bottle',
        'expires_at' => now()->addMonths(12),
      ],
      [
        'name' => 'Tea Bags',
        'cost_per_unit' => 1000,
        'stock' => 500,
        'min_stock' => 100,
        'unit' => 'pcs',
        'expires_at' => now()->addMonths(12),
      ],
      [
        'name' => 'Coffee Sachets',
        'cost_per_unit' => 2000,
        'stock' => 400,
        'min_stock' => 80,
        'unit' => 'pcs',
        'expires_at' => now()->addMonths(12),
      ],
    ];

    Inventory::insert($inventories);
  }
}
