<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $phoneNumber = 8000000000;

        return [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "phone" => "0" . $phoneNumber++,
            "points" => 1000,
            "password" => Hash::make("password"),
            "image" => "image/profile.jpg",
        ];
    }
}
