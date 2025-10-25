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
        'description' => 'Nasi goreng spesial dengan telur, ayam, dan sayuran segar',
        'image' => 'menu/nasi_goreng.png',
        'price' => 50000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Sate Ayam',
        'description' => 'Sate ayam bumbu kacang dengan lontong dan acar',
        'image' => 'menu/sate_ayam.png',
        'price' => 60000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Beef Steak',
        'description' => 'Steak daging sapi premium dengan saus mushroom dan kentang tumbuk',
        'image' => 'menu/beef_steak.png',
        'price' => 120000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Ikan Bakar',
        'description' => 'Ikan segar bakar dengan bumbu kecap dan sambal matah',
        'image' => 'menu/ikan_bakar.png',
        'price' => 75000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Capcay',
        'description' => 'Tumis sayuran segar dengan saus tiram',
        'image' => 'menu/capcay.png',
        'price' => 45000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Tahu Goreng',
        'description' => 'Tahu goreng crispy dengan saus kacang dan cabai',
        'image' => 'menu/tahu_goreng.png',
        'price' => 20000,
        'is_available' => true,
        'category_id' => 2,
      ],
      [
        'name' => 'Tempe Goreng',
        'description' => 'Tempe goreng renyah dengan sambal terasi',
        'image' => 'menu/tempe_goreng.png',
        'price' => 20000,
        'is_available' => true,
        'category_id' => 2,
      ],
      [
        'name' => 'Mie Goreng',
        'description' => 'Mie goreng spesial dengan telur, sayuran, dan ayam',
        'image' => 'menu/mie_goreng.png',
        'price' => 30000,
        'is_available' => true,
        'category_id' => 1,
      ],
      [
        'name' => 'Teh',
        'description' => 'Teh manis segar, tersedia panas atau dingin',
        'image' => 'menu/teh.png',
        'price' => 15000,
        'is_available' => true,
        'category_id' => 3,
      ],
      [
        'name' => 'Kopi',
        'description' => 'Kopi hitam atau susu premium, hot/iced',
        'image' => 'menu/kopi.png',
        'price' => 18000,
        'is_available' => true,
        'category_id' => 3,
      ],
    ];

    MenuItem::insert($menuItems);

    $this->command->info('✓ Created ' . count($menuItems) . ' menu items');
  }
}
