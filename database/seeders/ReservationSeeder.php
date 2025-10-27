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
    // Ambil orders yang sudah ada
    $orders = Order::whereNotNull('customer_id')->inRandomOrder()->get();
    if ($orders->isEmpty()) {
      $this->command->warn('ReservationSeeder: Tidak ada orders. Seed Order dulu.');
      return;
    }

    $tablesByCategory = Table::all()->groupBy(fn($t) => explode('-', $t->name, 2)[0] ?? 'Indoor');

    // Range: 30 hari lalu sampai 30 hari ke depan (HISTORY + FUTURE)
    $startDate = Carbon::today()->subDays(30);
    $endDate   = Carbon::today()->addDays(30);

    $reservations = [];
    $conflicts = [];
    $orderIndex = 0;
    $stats = ['completed' => 0, 'confirmed' => 0, 'pending' => 0, 'cancelled' => 0, 'no_show' => 0];

    $current = $startDate->copy();
    while ($current->lte($endDate)) {
      // Skip hari Minggu
      if ($current->isSunday()) {
        $current->addDay();
        continue;
      }

      $slotHours = range(10, 23); // 10:00–24:00
      foreach ($slotHours as $hour) {
        foreach ($tablesByCategory as $category => $tables) {
          $isWeekend = $current->isFriday() || $current->isSaturday();
          $isFarFuture = $current->gt(Carbon::today()->addDays(14));
          $isDeepPast = $current->lt(Carbon::today()->subDays(14));

          // Tingkat keramaian dinamis per jam
          $base = $isWeekend ? 55 : 30;
          if ($hour >= 18 && $hour <= 21) $base += 25; // Prime time dinner LEBIH RAMAI
          if ($hour >= 12 && $hour <= 14) $base += 15; // Lunch time
          if ($isFarFuture) $base = max(8, $base - 25);
          if ($isDeepPast)  $base = max(15, $base - 15);

          $fillPercent = max(5, min(85, $base + random_int(-8, 12)));
          $takeCount = max(0, intdiv($tables->count() * $fillPercent, 100));

          foreach ($tables->shuffle()->take($takeCount) as $table) {
            $start = Carbon::parse($current->toDateString() . ' ' . $hour . ':00:00');

            // Cegah duplikat di array (database akan enforce unique constraint)
            $key = "{$table->id}|{$current->toDateString()}|{$hour}";
            if (isset($reservations[$key])) continue;

            // Status dinamis
            if ($current->lt(Carbon::today())) {
              // === HISTORY ===
              $roll = random_int(1, 100);
              if ($current->lt(Carbon::today()->subDays(14))) {
                if ($roll <= 82) {
                  $status = 'completed';
                } elseif ($roll <= 93) {
                  $status = 'cancelled';
                } else {
                  $status = 'no_show';
                }
              } else {
                if ($roll <= 68) {
                  $status = 'completed';
                } elseif ($roll <= 83) {
                  $status = 'cancelled';
                } else {
                  $status = 'no_show';
                }
              }
            } elseif ($current->isToday()) {
              // === HARI INI ===
              $roll = random_int(1, 100);
              $currentHour = Carbon::now()->hour;

              if ($hour < $currentHour) {
                $status = $roll <= 75 ? 'completed' : ($roll <= 90 ? 'no_show' : 'cancelled');
              } elseif ($hour === $currentHour) {
                $status = $roll <= 60 ? 'confirmed' : ($roll <= 85 ? 'completed' : 'pending');
              } else {
                $status = $roll <= 70 ? 'confirmed' : 'pending';
              }
            } else {
              // === MASA DEPAN ===
              $roll = random_int(1, 100);
              $daysAhead = Carbon::today()->diffInDays($current);

              if ($daysAhead <= 2) {
                $status = $roll <= 85 ? 'confirmed' : 'pending';
                if (random_int(1, 100) <= 5) $status = 'cancelled';
              } elseif ($daysAhead <= 7) {
                $status = $roll <= 55 ? 'confirmed' : 'pending';
                if (random_int(1, 100) <= 8) $status = 'cancelled';
              } else {
                $status = $roll <= 75 ? 'pending' : 'confirmed';
                if (random_int(1, 100) <= 10) $status = 'cancelled';
              }
            }

            // Cycle through orders
            $order = $orders[$orderIndex % $orders->count()];
            $orderIndex++;

            // Timestamp realistis
            $createdAt = $current->copy()->subDays(random_int(1, 10))->setTime(random_int(8, 20), random_int(0, 59));
            if ($createdAt->isFuture()) $createdAt = Carbon::now()->subHours(random_int(1, 48));

            // Simpan reservasi pertama
            $reservations[] = [
              'order_id' => $order->id,
              'table_id' => $table->id,
              'reservation_date' => $current->toDateString(),
              'start_time' => $start->format('H:i:s'),
              'end_time' => $start->copy()->addHour()->format('H:i:s'),
              'status' => $status,
              'created_at' => $createdAt,
              'updated_at' => now(),
            ];
            $stats[$status]++;

            // ============================================
            // KASUS KONFLIK DITINGKATKAN
            // ============================================
            $shouldCreateConflict = false;
            $conflictChance = 0;

            if ($current->gte(Carbon::today()) && in_array($status, ['pending', 'confirmed'])) {
              // PRIME TIME (18-21) -> 35% chance konflik
              if ($hour >= 18 && $hour <= 21) {
                $conflictChance = 35;
              }
              // LUNCH TIME (12-14) -> 25% chance
              elseif ($hour >= 12 && $hour <= 14) {
                $conflictChance = 25;
              }
              // Weekend ALL DAY -> +10%
              if ($isWeekend) {
                $conflictChance += 10;
              }
              // Near future (1-3 hari) -> +10%
              $daysAhead = Carbon::today()->diffInDays($current);
              if ($daysAhead <= 3) {
                $conflictChance += 10;
              }
              // VIP & Terrace lebih sering konflik -> +5%
              if (in_array($category, ['VIP', 'Terrace'])) {
                $conflictChance += 5;
              }

              $shouldCreateConflict = random_int(1, 100) <= $conflictChance;
            }

            if ($shouldCreateConflict) {
              // Jumlah konflik: 1-3 reservasi tambahan (total 2-4 di slot sama)
              $conflictCount = random_int(1, 3);
              $conflictOrders = [];

              for ($c = 0; $c < $conflictCount; $c++) {
                $conflictOrder = $orders[($orderIndex + $c) % $orders->count()];
                $conflictOrders[] = $conflictOrder->id;

                // Semua konflik dimulai sebagai pending
                $conflictStatus = 'pending';

                // Created time berbeda (simulasi race condition dalam 1 jam)
                $conflictCreatedAt = $createdAt->copy()->addMinutes(random_int(1, 55));

                $reservations[] = [
                  'order_id' => $conflictOrder->id,
                  'table_id' => $table->id,
                  'reservation_date' => $current->toDateString(),
                  'start_time' => $start->format('H:i:s'),
                  'end_time' => $start->copy()->addHour()->format('H:i:s'),
                  'status' => $conflictStatus,
                  'created_at' => $conflictCreatedAt,
                  'updated_at' => now(),
                ];

                $stats[$conflictStatus]++;
              }

              $orderIndex += $conflictCount;

              // Track konflik untuk statistik
              $conflicts[] = [
                'table' => $table->name,
                'date' => $current->toDateString(),
                'hour' => $hour,
                'count' => $conflictCount + 1, // Total reservasi di slot ini
                'orders' => array_merge([$order->id], $conflictOrders),
              ];
            }
          }
        }
      }
      $current->addDay();
    }

    // Insert ke database
    Reservation::truncate();
    $chunks = array_chunk($reservations, 500);
    foreach ($chunks as $chunk) {
      Reservation::insert($chunk);
    }

    $total = count($reservations);
    $conflictSlots = count($conflicts);
    $totalConflictReservations = array_sum(array_column($conflicts, 'count'));

    $this->command->info("✓ ReservationSeeder: {$total} reservasi dibuat (30 hari history + 30 hari future)");
    $this->command->info("  ✓ Completed: {$stats['completed']}");
    $this->command->info("  ✓ Confirmed: {$stats['confirmed']}");
    $this->command->info("  ⏳ Pending: {$stats['pending']}");
    $this->command->info("  ✗ Cancelled: {$stats['cancelled']}");
    $this->command->info("  ⚠ No Show: {$stats['no_show']}");
    $this->command->line("");
    $this->command->info("⚔ KONFLIK RESERVASI:");
    $this->command->info("  → {$conflictSlots} slot dengan multiple booking");
    $this->command->info("  → {$totalConflictReservations} total reservasi di slot konflik");

    if ($conflictSlots > 0) {
      $this->command->warn("  → Cashier perlu memilih salah satu untuk di-approve");
      $this->command->line("");
      $this->command->info("Sample Konflik (5 teratas):");

      // Urutkan berdasarkan jumlah konflik terbanyak
      usort($conflicts, fn($a, $b) => $b['count'] <=> $a['count']);

      foreach (array_slice($conflicts, 0, 5) as $conf) {
        $dayName = Carbon::parse($conf['date'])->locale('id')->dayName;
        $this->command->line("  • {$conf['table']} | {$dayName} {$conf['date']} {$conf['hour']}:00 | {$conf['count']} reservasi | Orders: " . implode(', ', $conf['orders']));
      }

      // Breakdown by hour
      $this->command->line("");
      $this->command->info("Jam Paling Rawan Konflik:");
      $hourlyConflicts = [];
      foreach ($conflicts as $c) {
        $hourlyConflicts[$c['hour']] = ($hourlyConflicts[$c['hour']] ?? 0) + 1;
      }
      arsort($hourlyConflicts);
      foreach (array_slice($hourlyConflicts, 0, 5, true) as $h => $count) {
        $this->command->line("  • Jam {$h}:00 → {$count} konflik");
      }
    }
  }
}
