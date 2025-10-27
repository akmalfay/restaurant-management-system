<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\StaffDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $chefs = StaffDetail::where('role', 'chef')->where('is_active', true)->get();
    $waiters = StaffDetail::where('role', 'waiter')->where('is_active', true)->get();
    $cashiers = StaffDetail::where('role', 'cashier')->where('is_active', true)->get();

    if ($chefs->isEmpty() || $waiters->isEmpty() || $cashiers->isEmpty()) {
      $this->command->warn('Tidak ada staff aktif. Jalankan StaffDetailSeeder terlebih dahulu.');
      return;
    }

    $schedules = [];

    // Generate dari 4 minggu yang lalu sampai 4 minggu ke depan (total 8 minggu)
    $startDate = Carbon::now()->subWeeks(4)->startOfWeek(Carbon::MONDAY);
    $endDate = Carbon::now()->addWeeks(4)->endOfWeek(Carbon::SUNDAY);

    $currentDate = $startDate->copy();
    $weekNumber = 0;

    while ($currentDate->lte($endDate)) {
      // Skip Minggu (hari libur)
      if ($currentDate->isSunday()) {
        if ($currentDate->isEndOfWeek()) {
          $weekNumber++;
        }
        $currentDate->addDay();
        continue;
      }

      $isEvenWeek = $weekNumber % 2 === 0;

      // === CHEF ROTATION ===
      // Bagi chef jadi 2 grup untuk rotasi mingguan
      $halfChef = max(1, intval(ceil($chefs->count() / 2)));
      $chefGroup1 = $chefs->take($halfChef);
      $chefGroup2 = $chefs->slice($halfChef);

      // Minggu genap: Group1 morning, Group2 night
      // Minggu ganjil: Group2 morning, Group1 night
      $morningChefs = $isEvenWeek ? $chefGroup1 : $chefGroup2;
      $nightChefs = $isEvenWeek ? $chefGroup2 : $chefGroup1;

      foreach ($morningChefs as $chef) {
        $schedules[] = [
          'staff_id' => $chef->id,
          'schedule_date' => $currentDate->toDateString(),
          'shift' => 'morning',
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }

      foreach ($nightChefs as $chef) {
        $schedules[] = [
          'staff_id' => $chef->id,
          'schedule_date' => $currentDate->toDateString(),
          'shift' => 'night',
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }

      // === WAITER ROTATION ===
      // Rotasi per hari untuk variasi
      $waiterCount = $waiters->count();
      if ($waiterCount >= 2) {
        $dayOfWeek = $currentDate->dayOfWeek; // 1=Mon, 2=Tue, ...
        $morningWaiterIndex = $dayOfWeek % $waiterCount;
        $nightWaiterIndex = ($dayOfWeek + 1) % $waiterCount;

        $morningWaiter = $waiters[$morningWaiterIndex];
        $nightWaiter = $waiters[$nightWaiterIndex];
      } else {
        $morningWaiter = $waiters->first();
        $nightWaiter = $waiters->first();
      }

      $schedules[] = [
        'staff_id' => $morningWaiter->id,
        'schedule_date' => $currentDate->toDateString(),
        'shift' => 'morning',
        'created_at' => now(),
        'updated_at' => now(),
      ];

      $schedules[] = [
        'staff_id' => $nightWaiter->id,
        'schedule_date' => $currentDate->toDateString(),
        'shift' => 'night',
        'created_at' => now(),
        'updated_at' => now(),
      ];

      // === CASHIER ROTATION ===
      // Rotasi per hari
      $cashierCount = $cashiers->count();
      if ($cashierCount >= 2) {
        $dayOfWeek = $currentDate->dayOfWeek;
        $morningCashierIndex = $dayOfWeek % $cashierCount;
        $nightCashierIndex = ($dayOfWeek + 1) % $cashierCount;

        $morningCashier = $cashiers[$morningCashierIndex];
        $nightCashier = $cashiers[$nightCashierIndex];
      } else {
        $morningCashier = $cashiers->first();
        $nightCashier = $cashiers->first();
      }

      $schedules[] = [
        'staff_id' => $morningCashier->id,
        'schedule_date' => $currentDate->toDateString(),
        'shift' => 'morning',
        'created_at' => now(),
        'updated_at' => now(),
      ];

      $schedules[] = [
        'staff_id' => $nightCashier->id,
        'schedule_date' => $currentDate->toDateString(),
        'shift' => 'night',
        'created_at' => now(),
        'updated_at' => now(),
      ];

      $currentDate->addDay();

      // Increment week number setiap akhir minggu
      if ($currentDate->isSunday()) {
        $weekNumber++;
      }
    }

    // Hapus data lama (opsional)
    Schedule::truncate();

    // Bulk insert
    if (!empty($schedules)) {
      foreach (array_chunk($schedules, 500) as $chunk) {
        Schedule::insert($chunk);
      }
      $this->command->info('ScheduleSeeder: berhasil membuat ' . count($schedules) . ' schedule entries.');
    }
  }
}
