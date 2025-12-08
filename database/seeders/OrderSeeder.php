<?php

namespace Database\Seeders;

use App\Models\CustomerDetail;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $customers = CustomerDetail::all();
    $types = ['dine_in', 'takeway', 'delivery'];
    // include cancel (rare)
    $statuses = ['pending', 'preparing', 'ready', 'cancel'];

    $orders = [];

    // create 120 orders across past 10 days and next 3 days
    $now = Carbon::now();
    for ($i = 0; $i < 120; $i++) {
      $date = $now->copy()->subDays(random_int(0, 7))->addHours(random_int(-24, 48));
      $cust = random_int(1, 100) <= 70 ? $customers->random() : null; // 70% assigned customers

      // make cancel relatively uncommon (~6%)
      $status = (random_int(1, 100) <= 6) ? 'cancel' : $statuses[array_rand(['pending', 'preparing', 'ready'])];

      $orders[] = [
        'customer_id' => $cust ? $cust->id : null,
        'type' => $types[array_rand($types)],
        'total' => 0,
        'status' => $status,
        'points_redeemed' => null,
        'order_time' => $date,
        'created_at' => $date,
        'updated_at' => $date,
      ];
    }

    Order::insert($orders);
    $this->command->info('✓ OrderSeeder: created ' . count($orders) . ' orders');
  }
}
