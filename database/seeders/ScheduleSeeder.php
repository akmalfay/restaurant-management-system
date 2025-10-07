<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\StaffDetail;
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
    $chefs = StaffDetail::where('role', 'chef')->get();
    $waiters = StaffDetail::where('role', 'waiter')->get();
    $cashiers = StaffDetail::where('role', 'cashier')->get();

    $schedules = [];
    $startDate = Carbon::now()->startOfWeek();

    for ($day = 0; $day < 14; $day++) {
      $currentDate = $startDate->copy()->addDays($day);

      if ($currentDate->isSunday()) {
        continue;
      }

      $isFirstWeek = $day < 7;

      $chefChunks = $chefs->chunk(3);
      $morningChefs = $isFirstWeek ? $chefChunks[0] : $chefChunks[1];
      $nightChefs = $isFirstWeek ? $chefChunks[1] : $chefChunks[0];

      foreach ($morningChefs as $staff) {
        $schedules[] = [
          'staff_id' => $staff->id,
          'schedule_date' => $currentDate,
          'shift' => 'morning',
        ];
      }
      foreach ($nightChefs as $staff) {
        $schedules[] = [
          'staff_id' => $staff->id,
          'schedule_date' => $currentDate,
          'shift' => 'night',
        ];
      }

      $morningWaiter = $isFirstWeek ? $waiters[0] : $waiters[1];
      $nightWaiter = $isFirstWeek ? $waiters[1] : $waiters[0];
      $schedules[] = [
        'staff_id' => $morningWaiter->id,
        'schedule_date' => $currentDate,
        'shift' => 'morning',
      ];
      $schedules[] = [
        'staff_id' => $nightWaiter->id,
        'schedule_date' => $currentDate,
        'shift' => 'night',
      ];

      $morningCashier = $isFirstWeek ? $cashiers[0] : $cashiers[1];
      $nightCashier = $isFirstWeek ? $cashiers[1] : $cashiers[0];
      $schedules[] = [
        'staff_id' => $morningCashier->id,
        'schedule_date' => $currentDate,
        'shift' => 'morning',
      ];
      $schedules[] = [
        'staff_id' => $nightCashier->id,
        'schedule_date' => $currentDate,
        'shift' => 'night',
      ];
    }

    Schedule::insert($schedules);
  }
}
