<?php

namespace App\Helpers;

use Faker\Generator as Faker;

class DataGenerator
{
  public static function generateIndonesianAddress(): string
  {
    $streets = [
      'Jl. Sudirman',
      'Jl. Thamrin',
      'Jl. Gatot Subroto',
      'Jl. HR Rasuna Said',
      'Jl. Kuningan Raya',
      'Jl. Senopati',
      'Jl. Kemang Raya',
      'Jl. Panjang',
      'Jl. Casablanca',
    ];

    $areas = [
      'Jakarta Pusat',
      'Jakarta Selatan',
      'Jakarta Barat',
      'Kebayoran Baru',
      'Menteng',
      'Setiabudi',
      'Kemang',
    ];

    $buildings = [
      'Komplek Perumahan Green Valley',
      'Apartemen The Peak',
      'Ruko Golden Plaza',
      'Cluster Bintaro Residence',
      '',
    ];

    $street = fake()->randomElement($streets);
    $number = fake()->numberBetween(1, 999);
    $building = fake()->randomElement($buildings);
    $area = fake()->randomElement($areas);

    if ($building) {
      return "{$street} No. {$number}, {$building}, {$area}";
    }

    return "{$street} No. {$number}, {$area}";
  }

  public static function generateRandomDOB(): string
  {
    $minAge = 18;
    $maxAge = 65;

    $yearOfBirth = now()->year - fake()->numberBetween($minAge, $maxAge);
    $month = fake()->numberBetween(1, 12);
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $yearOfBirth);
    $day = fake()->numberBetween(1, $daysInMonth);

    return sprintf('%04d-%02d-%02d', $yearOfBirth, $month, $day);
  }
}
