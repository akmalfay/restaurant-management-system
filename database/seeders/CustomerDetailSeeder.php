<?php

namespace Database\Seeders;

use App\Models\CustomerDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerDetailSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $customerUsers = User::where('user_type', 'customer')->get();

    foreach ($customerUsers as $customer) {
      CustomerDetail::create([
        'user_id' => $customer->id,
        'points' => 1000,
      ]);
    }
  }
}
