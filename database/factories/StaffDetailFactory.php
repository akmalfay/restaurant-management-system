<?php

namespace Database\Factories;

use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffDetailFactory extends Factory
{
  protected $model = StaffDetail::class;

  public function definition(): array
  {
    $positions = [
      ['position' => 'Head Chef'],
      ['position' => 'Sous Chef'],
      ['position' => 'Line Cook'],
      ['position' => 'Senior Waiter'],
      ['position' => 'Waiter'],
      ['position' => 'Cashier'],
      ['position' => 'Bartender'],
      ['position' => 'Host/Hostess'],
    ];

    $selectedPosition = fake()->randomElement($positions);

    return [
      'user_id' => User::factory(),
      'position' => $selectedPosition['position'],
      'salary' => $selectedPosition['salary'],
      'hire_date' => fake()->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
    ];
  }
}
