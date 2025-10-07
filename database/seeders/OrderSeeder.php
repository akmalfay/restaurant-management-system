<?php

namespace Database\Seeders;

use App\Models\CustomerDetail;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $ordersDineIn = CustomerDetail::take(3)->get();
    $ordersTakeway = CustomerDetail::skip(3)->take(3)->get();

    $orders = [];

    // dine-in
    foreach ($ordersDineIn as $order) {
      $orders[] = [
        'customer_id' => $order->id,
        'type' => 'dine_in',
        'total' => 0,
        'order_time' => now()->addMinutes(10),
        'points_redeemed' => 0,
        'status' => 'ready',
      ];
    }

    foreach ($ordersTakeway as $order) {
      $orders[] = [
        'customer_id' => $order->id,
        'type' => 'delivery',
        'total' => 0,
        'order_time' => now()->addMinutes(),
        'points_redeemed' => 0,
        'status' => 'preparing',
      ];
    }

    for ($i = 0; $i < 3; $i++) {
      $orders[] = [
        'customer_id' => null,
        'type' => 'takeway',
        'total' => 0,
        'order_time' => now(),
        'points_redeemed' => 0,
        'status' => 'pending',
      ];
    }

    Order::insert($orders);
  }
}
