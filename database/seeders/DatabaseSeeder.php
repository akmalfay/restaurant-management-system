<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->command->info('🌱 Starting database seeding...');

    // 1. Users & Authentication
    $this->call([
      UserSeeder::class,
      CustomerDetailSeeder::class,
      StaffDetailSeeder::class,
    ]);

    // 2. Restaurant Setup
    $this->call([
      CategorySeeder::class,
      MenuItemSeeder::class,
      TableSeeder::class,
    ]);

    // 3. Inventory System (URUTAN PENTING!)
    $this->call([
      InventorySeeder::class,         // Buat inventory dulu
      InventoryBatchSeeder::class,    // Lalu buat batch
      StockMovementSeeder::class,     // Terakhir movement
    ]);

    // 4. Orders & Transactions
    $this->call([
      OrderSeeder::class,
      OrderItemSeeder::class,
    ]);

    // 5. Other Features
    $this->call([
      ReservationSeeder::class,
      ScheduleSeeder::class,
      LoyaltyPointSeeder::class,
    ]);

    $this->command->info('✅ Database seeding completed successfully!');
  }
}
