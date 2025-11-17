<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $menuItems = MenuItem::all();
    $orders = Order::all();
    if ($menuItems->isEmpty() || $orders->isEmpty()) {
      $this->command->warn('OrderItemSeeder: menuItems or orders missing');
      return;
    }

    $items = [];
    foreach ($orders as $order) {
      $count = random_int(1, 5);
      $total = 0;
      $picked = $menuItems->shuffle()->take($count);
      foreach ($picked as $mi) {
        $qty = random_int(1, 3);
        $price = $mi->price;
        $items[] = [
          'order_id' => $order->id,
          'menu_id' => $mi->id,
          'quantity' => $qty,
          'price' => $price * $qty,
          'created_at' => now(),
          'updated_at' => now(),
        ];
        $total += $price * $qty;
      }
      Order::where('id', $order->id)->update(['total' => $total]);
    }

    // bulk insert
    $chunks = array_chunk($items, 500);
    foreach ($chunks as $c) OrderItem::insert($c);

    $this->command->info('✓ OrderItemSeeder: inserted items for orders');
  }
}
