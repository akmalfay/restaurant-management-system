<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Staff;
use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::take(5)->get();
        $tables = Table::all();

        $status = ["pending", "preparing", "ready", "served", "completed"];
        $type = ["dine_in", "takeway", "delivery"];

        foreach ($customers as $customer) {
            $randStatus = rand(0, count($status) - 1);
            $randType = rand(0, count($type) - 1);

            $staffId = null;
            if ($type[$randType] !== "delivery") {
                $staff = Staff::inRandomOrder()->first();
                $staffId = $staff->id;
            }

            Order::create([
                "table_id" => $tables->random()->id,
                "staff_id" => $staffId,
                "customer_id" => $customer->id,
                "type" => $type[$randType],
                "status" => $status[$randStatus],
                "total" => 0,
            ]);
        }
    }
}
