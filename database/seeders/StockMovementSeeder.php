<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = [
            ["inventory_id" => 1, "type" => "purchase", "quantity" => 200],
            ["inventory_id" => 1, "type" => "usage", "quantity" => 150],
            ["inventory_id" => 1, "type" => "waste", "quantity" => 10],

            ["inventory_id" => 2, "type" => "purchase", "quantity" => 100],
            ["inventory_id" => 2, "type" => "usage", "quantity" => 80],
            ["inventory_id" => 2, "type" => "waste", "quantity" => 5],

            ["inventory_id" => 3, "type" => "purchase", "quantity" => 60],
            ["inventory_id" => 3, "type" => "usage", "quantity" => 40],
            ["inventory_id" => 3, "type" => "waste", "quantity" => 3],

            ["inventory_id" => 4, "type" => "purchase", "quantity" => 80],
            ["inventory_id" => 4, "type" => "usage", "quantity" => 60],
            ["inventory_id" => 4, "type" => "waste", "quantity" => 4],

            ["inventory_id" => 5, "type" => "purchase", "quantity" => 700],
            ["inventory_id" => 5, "type" => "usage", "quantity" => 500],
            ["inventory_id" => 5, "type" => "waste", "quantity" => 20],

            ["inventory_id" => 6, "type" => "purchase", "quantity" => 30],
            ["inventory_id" => 6, "type" => "usage", "quantity" => 20],
            ["inventory_id" => 6, "type" => "waste", "quantity" => 1],

            ["inventory_id" => 7, "type" => "purchase", "quantity" => 10],
            ["inventory_id" => 7, "type" => "usage", "quantity" => 6],
            ["inventory_id" => 7, "type" => "waste", "quantity" => 1],

            ["inventory_id" => 8, "type" => "purchase", "quantity" => 10],
            ["inventory_id" => 8, "type" => "usage", "quantity" => 6],
            ["inventory_id" => 8, "type" => "waste", "quantity" => 1],

            ["inventory_id" => 9, "type" => "purchase", "quantity" => 20],
            ["inventory_id" => 9, "type" => "usage", "quantity" => 15],
            ["inventory_id" => 9, "type" => "waste", "quantity" => 2],

            ["inventory_id" => 10, "type" => "purchase", "quantity" => 25],
            ["inventory_id" => 10, "type" => "usage", "quantity" => 18],
            ["inventory_id" => 10, "type" => "waste", "quantity" => 1],
        ];

        foreach ($stocks as $stock) {
            StockMovement::create($stock);
        }
    }
}
