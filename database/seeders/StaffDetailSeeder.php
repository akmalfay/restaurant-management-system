<?php

namespace Database\Seeders;

use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffDetailSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = User::where('user_type', 'staff')->orderBy('id')->get();
    if ($users->isEmpty()) {
      $this->command->warn('StaffDetailSeeder: tidak ada staff.');
      return;
    }

    // assign roles safely according to availability
    $chefs = $users->slice(0, 6);
    $cashiers = $users->slice(6, 2);
    $waiters = $users->slice(8);

    foreach ($chefs as $user) {
      StaffDetail::firstOrCreate(
        ['user_id' => $user->id],
        ['role' => 'chef', 'is_active' => true, 'joined_at' => now()]
      );
    }

    foreach ($cashiers as $user) {
      StaffDetail::firstOrCreate(
        ['user_id' => $user->id],
        ['role' => 'cashier', 'is_active' => true, 'joined_at' => now()]
      );
    }

    foreach ($waiters as $user) {
      // remaining staff default to waiter
      StaffDetail::firstOrCreate(
        ['user_id' => $user->id],
        ['role' => 'waiter', 'is_active' => true, 'joined_at' => now()]
      );
    }

    $this->command->info('✓ StaffDetailSeeder: staff roles assigned (safe).');
  }
}
