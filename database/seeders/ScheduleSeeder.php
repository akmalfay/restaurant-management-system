<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dates = [];
        $today = Carbon::today();

        for ($i = 0; $i < 14; $i++) {
            $day = $today->copy()->addDays($i);
            if ($day->isSunday()) {
                continue;
            }
            $dates[] = $day;
        }

        $waiters = Staff::where("role", "waiter")->take(2)->get();
        $cashiers = Staff::where("role", "cashier")->take(2)->get();
        $chefs = Staff::where("role", "chef")->take(6)->get();

        $shifts = ["morning", "night"];

        foreach ($dates as $index => $date) {
            $shiftIndex = $index < 6 ? 0 : 1;

            foreach ($waiters as $i => $waiter) {
                Schedule::create([
                    "staff_id" => $waiter->id,
                    "date" => $date,
                    "shift" => $shifts[($shiftIndex + $i) % 2],
                ]);
            }

            foreach ($cashiers as $i => $cashier) {
                Schedule::create([
                    "staff_id" => $cashier->id,
                    "date" => $date,
                    "shift" => $shifts[($shiftIndex + $i) % 2],
                ]);
            }

            $half = ceil($chefs->count() / 2);

            $morningChefs = $shiftIndex
                ? $chefs->slice(0, $half)
                : $chefs->slice($half);
            $nightChefs = $shiftIndex
                ? $chefs->slice($half)
                : $chefs->slice(0, $half);

            foreach ($morningChefs as $chef) {
                Schedule::create([
                    "staff_id" => $chef->id,
                    "date" => $date,
                    "shift" => "morning",
                ]);
            }

            foreach ($nightChefs as $chef) {
                Schedule::create([
                    "staff_id" => $chef->id,
                    "date" => $date,
                    "shift" => "night",
                ]);
            }
        }
    }
}
