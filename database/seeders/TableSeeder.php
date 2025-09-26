<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listStatus = ["available", "occupied", "reserved", "maintenance"];

        $categories = [
            "A" => 2,
            "B" => 3,
            "C" => 4,
            "VIP" => 2,
        ];

        $tables = [];

        foreach ($categories as $prefix => $capacity) {
            for ($i = 1; $i <= 10; $i++) {
                $tables[] = [
                    "number" =>
                        $prefix . "-" . str_pad($i, 2, "0", STR_PAD_LEFT),
                    "capacity" => $capacity,
                    "status" => $listStatus[fake()->numberBetween(0, 3)],
                ];
            }
        }

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
