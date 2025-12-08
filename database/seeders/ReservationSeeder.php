<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $orders = Order::whereNotNull('customer_id')->inRandomOrder()->get();
    if ($orders->isEmpty()) {
      $this->command->warn('ReservationSeeder: Tidak ada orders. Seed Order dulu.');
      return;
    }

    $tables = Table::orderBy('id')->get();
    if ($tables->isEmpty()) {
      $this->command->warn('ReservationSeeder: Tidak ada tables. Seed Table dulu.');
      return;
    }

    $startDate = Carbon::today()->subDays(30);
    $endDate   = Carbon::today()->addDays(30);

    $reservations = []; // keyed to avoid duplicates
    $orderIndex = 0;
    $stats = ['upcoming' => 0, 'ongoing' => 0, 'finished' => 0];

    $current = $startDate->copy();
    while ($current->lte($endDate)) {
      if ($current->isSunday()) {
        $current->addDay();
        continue;
      }

      $hours = range(10, 23);
      foreach ($hours as $hour) {
        foreach ($tables->shuffle() as $table) {
          // skip tables marked unavailable (maintenance) if column exists
          if (isset($table->is_available) && $table->is_available === false) continue;

          // random fill chance per table (keeps dataset realistic, no conflicts)
          $chance = random_int(1, 100);
          if ($chance > 30) continue; // ~30% occupancy to avoid overcrowding in seed

          $start = Carbon::parse($current->toDateString() . ' ' . $hour . ':00:00');
          $end = (clone $start)->addHour();

          // key prevents duplicates: table|date|hour
          $key = "{$table->id}|{$current->toDateString()}|{$hour}";
          if (isset($reservations[$key])) continue;

          // determine status by time relative to now
          if ($current->lt(Carbon::today())) {
            $status = 'finished';
          } elseif ($current->isToday()) {
            $nowHour = Carbon::now()->hour;
            if ($hour < $nowHour) {
              $status = 'finished';
            } elseif ($hour === $nowHour) {
              $status = 'ongoing';
            } else {
              $status = 'upcoming';
            }
          } else {
            // future date
            $status = 'upcoming';
          }

          $order = $orders[$orderIndex % $orders->count()];
          $orderIndex++;

          $createdAt = $current->copy()->subDays(random_int(1, 10))->setTime(random_int(8, 20), random_int(0, 59));
          if ($createdAt->isFuture()) $createdAt = Carbon::now()->subHours(random_int(1, 48));

          $reservations[$key] = [
            'order_id' => $order->id,
            'table_id' => $table->id,
            'reservation_date' => $current->toDateString(),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'status' => $status,
            'created_at' => $createdAt,
            'updated_at' => now(),
          ];

          $stats[$status]++;
        }
      }
      $current->addDay();
    }

    // persist
    Reservation::truncate();
    $chunks = array_chunk(array_values($reservations), 500);
    foreach ($chunks as $chunk) {
      Reservation::insert($chunk);
    }

    $total = count($reservations);
    $this->command->info("✓ ReservationSeeder: {$total} reservasi dibuat (no conflicts)");
    $this->command->info("  • Upcoming: {$stats['upcoming']}");
    $this->command->info("  • Ongoing:  {$stats['ongoing']}");
    $this->command->info("  • Finished: {$stats['finished']}");
  }
}
