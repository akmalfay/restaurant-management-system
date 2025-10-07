<?php

namespace Database\Seeders;

use App\Models\CustomerDetail;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoyaltyPointSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $orders = Order::take(5)->get();
    $customers = CustomerDetail::take(5)->get();

    $type = ['earn', 'redeem'];

    for ($i = 0; $i < count($orders); $i++) {
      LoyaltyPoint::create([
        'customer_id' => $customers[$i]->id,
        'order_id' => $orders[$i]->id,
        'points' => rand(10, 100),
        'type' => $type[rand(0, 1)],
      ]);
    }

    $getLoyaltyPoints = LoyaltyPoint::all();

    // update total point customer
    foreach ($customers as $customer) {
      $totalPoint = $customer->points;

      foreach ($getLoyaltyPoints as $loyaltyPoint) {
        if ($loyaltyPoint->customer_id === $customer->id) {
          $totalPoint =
            $loyaltyPoint->type === 'earn'
            ? $totalPoint + $loyaltyPoint->points
            : $totalPoint - $loyaltyPoint->points;
        }
      }

      CustomerDetail::where('id', $customer->id)->update([
        'points' => $totalPoint,
      ]);
    }
  }
}
