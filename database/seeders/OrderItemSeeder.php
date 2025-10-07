<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

    $orderItems = [];

    // 4 order buy 1 item
    for ($i = 0; $i < 4; $i++) {
      $orderItems[] = [
        'order_id' => $orders[$i]->id,
        'menu_id' => $menuItems[$i]->id,
        'quantity' => 1,
        'price' => $menuItems[$i]->price,
      ];
    }

    // 1 order buy 3 items
    for ($i = 0; $i < 3; $i++) {
      $orderItems[] = [
        'order_id' => 5,
        'menu_id' => $menuItems[$i]->id,
        'quantity' => 1,
        'price' => $menuItems[$i]->price * ($i + 1),
      ];
    }

    OrderItem::insert($orderItems);

    // Update total price order
    foreach ($orders as $order) {
      $totalPrice = 0;

      foreach ($orderItems as $orderItem) {
        if ($orderItem['order_id'] === $order->id) {
          $totalPrice += $orderItem['price'];
        }
      }

      Order::where('id', $order->id)->update([
        'total' => $totalPrice,
      ]);
    }
  }
}
