<?php

namespace Database\Seeders;

use App\Models\Customer;
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
        $customers = Customer::take(5)->get();

        $type = ["earned", "redeemed"];

        for ($i = 0; $i < count($orders); $i++) {
            $randPoint = rand(10, 100);
            $randType = rand(0, 1);

            LoyaltyPoint::create([
                "customer_id" => $customers[$i]->id,
                "order_id" => $orders[$i]->id,
                "points" => $randPoint,
                "type" => $type[$randType],
                "expires_at" => now()->addDay(),
            ]);
        }

        $loyaltyPoints = LoyaltyPoint::all();
        $customers = LoyaltyPoint::take(5)->get();

        foreach ($customers as $customer) {
            $totalPoint = $customer->points;

            foreach ($loyaltyPoints as $loyaltyPoint) {
                if ($loyaltyPoint->customer_id === $customer->id) {
                    $totalPoint =
                        $loyaltyPoint->type === "earned"
                            ? $totalPoint + $loyaltyPoint->points
                            : $totalPoint - $loyaltyPoint->points;
                }
            }

            Customer::where("id", $customer->id)->update([
                "points" => $totalPoint,
            ]);
        }
    }
}
