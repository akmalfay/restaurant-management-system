<?php

namespace Database\Seeders;

use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            TableSeeder::class,
            StaffSeeder::class,
            ScheduleSeeder::class,
            CategorySeeder::class,
            InventorySeeder::class,
            StockMovementSeeder::class,
            MenuItemSeeder::class,
            RecipeIngredientSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            LoyaltyPointSeeder::class,
        ]);
    }
}
