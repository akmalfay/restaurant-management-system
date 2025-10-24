<?php

namespace Database\Factories;

use App\Models\CustomerDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerDetailFactory extends Factory
{
  protected $model = CustomerDetail::class;

  public function definition(): array
  {
    // Daftar jalan di Jakarta
    $streets = [
      'Jl. Sudirman',
      'Jl. Thamrin',
      'Jl. Gatot Subroto',
      'Jl. HR Rasuna Said',
      'Jl. Kuningan Raya',
      'Jl. Senopati',
      'Jl. Kemang Raya',
      'Jl. Panjang',
      'Jl. Teuku Nyak Arief',
      'Jl. Casablanca',
      'Jl. MT Haryono',
      'Jl. Ahmad Yani',
      'Jl. Sisingamangaraja',
      'Jl. Panglima Polim',
      'Jl. Cipete Raya',
    ];

    // Daftar kelurahan/area di Jakarta
    $areas = [
      'Jakarta Pusat',
      'Jakarta Selatan',
      'Jakarta Barat',
      'Jakarta Timur',
      'Jakarta Utara',
      'Kebayoran Baru',
      'Menteng',
      'Tanah Abang',
      'Setiabudi',
      'Tebet',
      'Kemang',
      'Pondok Indah',
    ];

    $street = fake()->randomElement($streets);
    $number = fake()->numberBetween(1, 999);
    $area = fake()->randomElement($areas);

    // Format: Jl. Sudirman No. 123, Jakarta Pusat
    $address = "{$street} No. {$number}, {$area}";

    return [
      'user_id' => User::factory(),
      'address' => $address,
      'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
    ];
  }
}
