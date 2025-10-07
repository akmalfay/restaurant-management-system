<?php

namespace Database\Seeders;

use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffDetailSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = User::where('user_type', 'staff')->get();
    $chefs = $users->slice(0, 6);
    $cashiers = $users->slice(6, 2);
    $waiters = $users->slice(8, 2);

    foreach ($chefs as $user) {
      StaffDetail::create([
        'user_id' => $user->id,
        'role' => 'chef',
        'is_active' => true,
        'joined_at' => now(),
      ]);
    }

    foreach ($cashiers as $user) {
      StaffDetail::create([
        'user_id' => $user->id,
        'role' => 'cashier',
        'is_active' => true,
        'joined_at' => now(),
      ]);
    }

    foreach ($waiters as $user) {
      StaffDetail::create([
        'user_id' => $user->id,
        'role' => 'waiter',
        'is_active' => true,
        'joined_at' => now(),
      ]);
    }
  }
}
