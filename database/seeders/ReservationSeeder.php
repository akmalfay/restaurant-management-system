<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $orders = Order::whereNotNull('customer_id')->take(4)->get();

    $idReservedTables = [1, 2, 11, 12, 21, 22, 31, 32];

    $dates = [
      Carbon::today(),
      Carbon::today()->addDay(),
      Carbon::today()->addDays(2),
    ];

    foreach ($dates as $date) {
      $start = $date->copy()->setTime(12, 0);

      foreach ($idReservedTables as $tableId) {
        foreach ($orders as $order) {
          Reservation::create([
            'order_id' => $order->id,
            'table_id' => $tableId,
            'status' => 'confirmed',
            'guests' => rand(1, 4),
            'reservation_time' => $start,
            'session' => rand(1, 2),
          ]);

          $skip = rand(0, 3);
          $start->addHours($skip);
        }
      }
    }
  }
}
