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

        $orderItems = [
            [
                "order_id" => $orders[0]->id,
                "menu_id" => $menuItems[0]->id,
                "quantity" => 1,
                "price" => $menuItems[0]->price,
            ],
            [
                "order_id" => $orders[1]->id,
                "menu_id" => $menuItems[1]->id,
                "quantity" => 2,
                "price" => $menuItems[1]->price * 2,
            ],
            [
                "order_id" => $orders[2]->id,
                "menu_id" => $menuItems[2]->id,
                "quantity" => 1,
                "price" => $menuItems[2]->price,
            ],
            [
                "order_id" => $orders[3]->id,
                "menu_id" => $menuItems[3]->id,
                "quantity" => 1,
                "price" => $menuItems[3]->price,
            ],
            [
                "order_id" => $orders[4]->id,
                "menu_id" => $menuItems[4]->id,
                "quantity" => 1,
                "price" => $menuItems[4]->price,
            ],
            [
                "order_id" => $orders[4]->id,
                "menu_id" => $menuItems[5]->id,
                "quantity" => 2,
                "price" => $menuItems[5]->price * 2,
            ],
            [
                "order_id" => $orders[4]->id,
                "menu_id" => $menuItems[6]->id,
                "quantity" => 3,
                "price" => $menuItems[6]->price * 3,
            ],
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }

        // Update total price order
        foreach ($orders as $order) {
            $totalPrice = 0;

            foreach ($orderItems as $orderItem) {
                if ($orderItem["order_id"] === $order->id) {
                    $totalPrice += $orderItem["price"];
                }
            }

            Order::where("id", $order->id)->update([
                "total" => $totalPrice,
            ]);
        }
    }
}
