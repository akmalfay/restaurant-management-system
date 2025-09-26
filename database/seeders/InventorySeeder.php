<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $inventories = [
            [
                "name" => "Rice",
                "item_code" => "ITEM-001",
                "cost_per_unit" => 12000,
                "stock" => 600,
                "min_stock" => 100,
                "unit" => "kg",
                "expires_at" => null,
            ],
            [
                "name" => "Chicken",
                "item_code" => "ITEM-002",
                "cost_per_unit" => 35000,
                "stock" => 300,
                "min_stock" => 50,
                "unit" => "kg",
                "expires_at" => now()->addDays(5),
            ],
            [
                "name" => "Beef",
                "item_code" => "ITEM-003",
                "cost_per_unit" => 95000,
                "stock" => 150,
                "min_stock" => 30,
                "unit" => "kg",
                "expires_at" => now()->addDays(5),
            ],
            [
                "name" => "Fish",
                "item_code" => "ITEM-004",
                "cost_per_unit" => 45000,
                "stock" => 200,
                "min_stock" => 40,
                "unit" => "kg",
                "expires_at" => now()->addDays(4),
            ],
            [
                "name" => "Eggs",
                "item_code" => "ITEM-005",
                "cost_per_unit" => 2000,
                "stock" => 2100,
                "min_stock" => 300,
                "unit" => "pcs",
                "expires_at" => now()->addWeeks(2),
            ],
            [
                "name" => "Cooking Oil",
                "item_code" => "ITEM-006",
                "cost_per_unit" => 25000,
                "stock" => 80,
                "min_stock" => 20,
                "unit" => "liter",
                "expires_at" => now()->addMonths(6),
            ],
            [
                "name" => "Soy Sauce",
                "item_code" => "ITEM-007",
                "cost_per_unit" => 15000,
                "stock" => 15,
                "min_stock" => 5,
                "unit" => "bottle",
                "expires_at" => now()->addMonths(12),
            ],
            [
                "name" => "Sweet Soy Sauce",
                "item_code" => "ITEM-008",
                "cost_per_unit" => 18000,
                "stock" => 15,
                "min_stock" => 5,
                "unit" => "bottle",
                "expires_at" => now()->addMonths(12),
            ],
            [
                "name" => "Chili",
                "item_code" => "ITEM-009",
                "cost_per_unit" => 40000,
                "stock" => 40,
                "min_stock" => 10,
                "unit" => "kg",
                "expires_at" => now()->addDays(7),
            ],
            [
                "name" => "Garlic",
                "item_code" => "ITEM-010",
                "cost_per_unit" => 30000,
                "stock" => 50,
                "min_stock" => 15,
                "unit" => "kg",
                "expires_at" => now()->addDays(14),
            ],
            [
                "name" => "Shallot",
                "item_code" => "ITEM-011",
                "cost_per_unit" => 32000,
                "stock" => 50,
                "min_stock" => 15,
                "unit" => "kg",
                "expires_at" => now()->addDays(14),
            ],
            [
                "name" => "Cabbage",
                "item_code" => "ITEM-012",
                "cost_per_unit" => 10000,
                "stock" => 70,
                "min_stock" => 20,
                "unit" => "kg",
                "expires_at" => now()->addDays(5),
            ],
            [
                "name" => "Carrot",
                "item_code" => "ITEM-013",
                "cost_per_unit" => 15000,
                "stock" => 50,
                "min_stock" => 15,
                "unit" => "kg",
                "expires_at" => now()->addDays(10),
            ],
            [
                "name" => "Potato",
                "item_code" => "ITEM-014",
                "cost_per_unit" => 12000,
                "stock" => 60,
                "min_stock" => 20,
                "unit" => "kg",
                "expires_at" => now()->addDays(14),
            ],
            [
                "name" => "Tofu",
                "item_code" => "ITEM-015",
                "cost_per_unit" => 5000,
                "stock" => 500,
                "min_stock" => 100,
                "unit" => "pcs",
                "expires_at" => now()->addDays(3),
            ],
            [
                "name" => "Tempeh",
                "item_code" => "ITEM-016",
                "cost_per_unit" => 6000,
                "stock" => 400,
                "min_stock" => 80,
                "unit" => "pcs",
                "expires_at" => now()->addDays(3),
            ],
            [
                "name" => "Instant Noodles",
                "item_code" => "ITEM-017",
                "cost_per_unit" => 3500,
                "stock" => 300,
                "min_stock" => 50,
                "unit" => "pack",
                "expires_at" => now()->addMonths(6),
            ],
            [
                "name" => "Mineral Water",
                "item_code" => "ITEM-018",
                "cost_per_unit" => 5000,
                "stock" => 500,
                "min_stock" => 100,
                "unit" => "bottle",
                "expires_at" => now()->addMonths(12),
            ],
            [
                "name" => "Tea Bags",
                "item_code" => "ITEM-019",
                "cost_per_unit" => 1000,
                "stock" => 500,
                "min_stock" => 100,
                "unit" => "pcs",
                "expires_at" => now()->addMonths(12),
            ],
            [
                "name" => "Coffee Sachets",
                "item_code" => "ITEM-020",
                "cost_per_unit" => 2000,
                "stock" => 400,
                "min_stock" => 80,
                "unit" => "pcs",
                "expires_at" => now()->addMonths(12),
            ],
        ];

        foreach ($inventories as $item) {
            Inventory::create($item);
        }
    }
}
