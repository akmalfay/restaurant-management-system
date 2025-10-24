<?php

namespace Database\Seeders;

use App\Helpers\DataGenerator;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = [
      'name' => 'Admin',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'user_type' => 'admin',
      'phone' => '081234567890',
      'email_verified_at' => now(),
      "address" => DataGenerator::generateIndonesianAddress(),
      'date_of_birth' => DataGenerator::generateRandomDOB(),
    ];

    User::create($user);

    // Generate 10 staff dengan factory
    User::factory()
      ->count(10)->staff()
      ->create();

    // Generate 20 customers dengan factory
    User::factory()
      ->count(20)
      ->create();
  }
}
