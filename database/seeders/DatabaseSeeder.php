<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      UserSeeder::class,
      StaffDetailSeeder::class,
      CustomerDetailSeeder::class,
      ScheduleSeeder::class,
      CategorySeeder::class,
      MenuItemSeeder::class,
      TableSeeder::class,
      OrderSeeder::class,
      OrderItemSeeder::class,
      ReservationSeeder::class,
      LoyaltyPointSeeder::class,
      InventorySeeder::class,
      StockMovementSeeder::class,
    ]);
  }
}
