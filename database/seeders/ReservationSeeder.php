<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::take(4)->get();

        $reservedTables = [1, 2, 11, 12, 21, 22, 31, 32];

        $dates = [
            Carbon::today(),
            Carbon::today()->addDay(),
            Carbon::today()->addDays(2),
        ];

        foreach ($dates as $date) {
            $start = $date->copy()->setTime(10, 0);

            foreach ($reservedTables as $table) {
                foreach ($customers as $customer) {
                    $started_at = $start->copy();
                    $ended_at = $start->copy()->addHours(2);

                    Reservation::create([
                        "customer_id" => $customer->id,
                        "table_id" => $table,
                        "date" => $date,
                        "status" => "confirmed",
                        "started_at" => $started_at,
                        "ended_at" => $ended_at,
                    ]);

                    $skip = rand(1, 3);
                    $start->addHours($skip);
                }
            }
        }
    }
}
