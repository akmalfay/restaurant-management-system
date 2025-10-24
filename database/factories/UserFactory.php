<?php

namespace Database\Factories;

use App\Helpers\DataGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password' => Hash::make('password'),
      'user_type' => 'customer',
      'image' => "profile/profile.jpg",
      'phone' => '08' . fake()->numerify('##########'),
      'date_of_birth' => DataGenerator::generateRandomDOB(),
      'address' => DataGenerator::generateIndonesianAddress(),
    ];
  }

  public function staff(): static
  {
    return $this->state(
      fn(array $attributes) => [
        'user_type' => 'staff',
      ],
    );
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}
