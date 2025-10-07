<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $menuItems = [
      [
        'name' => 'Nasi Goreng',
        'image' => 'image/nasi_goreng.png',
        'price' => 50000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Sate Ayam',
        'image' => 'image/sate_ayam.png',
        'price' => 60000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Beef Steak',
        'image' => 'image/beef_steak.png',
        'price' => 120000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Ikan Bakar',
        'image' => 'image/ikan_bakar.png',
        'price' => 75000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Capcay',
        'image' => 'image/capcay.png',
        'price' => 45000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Tahu Goreng',
        'image' => 'image/tahu_goreng.png',
        'price' => 20000,
        'is_available' => true,
        'category_id' => 2,
      ],
      [
        'name' => 'Tempe Goreng',
        'image' => 'image/tempe_goreng.png',
        'price' => 20000,
        'is_available' => true,
        'category_id' => 2,
      ],
      [
        'name' => 'Mie Goreng',
        'image' => 'image/mie_goreng.png',
        'price' => 30000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Teh',
        'image' => 'image/teh.png',
        'price' => 15000,
        'is_available' => true,
        'category_id' => 3,
      ],
      [
        'name' => 'Kopi',
        'image' => 'image/kopi.png',
        'price' => 18000,
        'is_available' => true,
        'category_id' => 3,
      ],
    ];

    MenuItem::insert($menuItems);
  }
}
