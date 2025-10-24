<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
  public function run(): void
  {
    $inventories = [
      // BAHAN MAKANAN
      [
        'name' => 'Daging Sapi',
        'stock' => 0, // Akan diupdate dari batch
        'min_stock' => 10.000,
        'unit' => 'kg',
        'cost_per_unit' => 150000,
        'expires_at' => null, // Akan diambil dari batch terdekat
      ],
      [
        'name' => 'Daging Ayam',
        'stock' => 0,
        'min_stock' => 15.000,
        'unit' => 'kg',
        'cost_per_unit' => 45000,
        'expires_at' => null,
      ],
      [
        'name' => 'Ikan Salmon',
        'stock' => 0,
        'min_stock' => 8.000,
        'unit' => 'kg',
        'cost_per_unit' => 280000,
        'expires_at' => null,
      ],
      [
        'name' => 'Udang Segar',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 180000,
        'expires_at' => null,
      ],

      // SAYURAN
      [
        'name' => 'Brokoli',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 25000,
        'expires_at' => null,
      ],
      [
        'name' => 'Wortel',
        'stock' => 0,
        'min_stock' => 8.000,
        'unit' => 'kg',
        'cost_per_unit' => 12000,
        'expires_at' => null,
      ],
      [
        'name' => 'Kentang',
        'stock' => 0,
        'min_stock' => 20.000,
        'unit' => 'kg',
        'cost_per_unit' => 8000,
        'expires_at' => null,
      ],
      [
        'name' => 'Bawang Bombay',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 35000,
        'expires_at' => null,
      ],
      [
        'name' => 'Tomat',
        'stock' => 0,
        'min_stock' => 10.000,
        'unit' => 'kg',
        'cost_per_unit' => 18000,
        'expires_at' => null,
      ],

      // DAIRY & TELUR
      [
        'name' => 'Susu Segar',
        'stock' => 0,
        'min_stock' => 15.000,
        'unit' => 'liter',
        'cost_per_unit' => 18000,
        'expires_at' => null,
      ],
      [
        'name' => 'Krim Kental',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'liter',
        'cost_per_unit' => 65000,
        'expires_at' => null,
      ],
      [
        'name' => 'Keju Cheddar',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 180000,
        'expires_at' => null,
      ],
      [
        'name' => 'Telur Ayam',
        'stock' => 0,
        'min_stock' => 100.000,
        'unit' => 'pcs',
        'cost_per_unit' => 2500,
        'expires_at' => null,
      ],

      // MINUMAN
      [
        'name' => 'Kopi Arabika',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 180000,
        'expires_at' => null,
      ],
      [
        'name' => 'Teh Hitam Premium',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 120000,
        'expires_at' => null,
      ],
      [
        'name' => 'Sirup Vanila',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'liter',
        'cost_per_unit' => 85000,
        'expires_at' => null,
      ],
      [
        'name' => 'Jus Jeruk Fresh',
        'stock' => 0,
        'min_stock' => 10.000,
        'unit' => 'liter',
        'cost_per_unit' => 25000,
        'expires_at' => null,
      ],

      // BAHAN KERING
      [
        'name' => 'Tepung Terigu',
        'stock' => 0,
        'min_stock' => 25.000,
        'unit' => 'kg',
        'cost_per_unit' => 12000,
        'expires_at' => null,
      ],
      [
        'name' => 'Gula Pasir',
        'stock' => 0,
        'min_stock' => 20.000,
        'unit' => 'kg',
        'cost_per_unit' => 14000,
        'expires_at' => null,
      ],
      [
        'name' => 'Garam',
        'stock' => 0,
        'min_stock' => 10.000,
        'unit' => 'kg',
        'cost_per_unit' => 5000,
        'expires_at' => null,
      ],

      // MINYAK & SAUS
      [
        'name' => 'Minyak Goreng',
        'stock' => 0,
        'min_stock' => 20.000,
        'unit' => 'liter',
        'cost_per_unit' => 16000,
        'expires_at' => null,
      ],
      [
        'name' => 'Minyak Zaitun',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'liter',
        'cost_per_unit' => 150000,
        'expires_at' => null,
      ],
      [
        'name' => 'Saus Tomat',
        'stock' => 0,
        'min_stock' => 10.000,
        'unit' => 'liter',
        'cost_per_unit' => 35000,
        'expires_at' => null,
      ],
      [
        'name' => 'Mayones',
        'stock' => 0,
        'min_stock' => 8.000,
        'unit' => 'liter',
        'cost_per_unit' => 45000,
        'expires_at' => null,
      ],

      // REMPAH & BUMBU
      [
        'name' => 'Lada Hitam',
        'stock' => 0,
        'min_stock' => 2.000,
        'unit' => 'kg',
        'cost_per_unit' => 180000,
        'expires_at' => null,
      ],
      [
        'name' => 'Bawang Putih',
        'stock' => 0,
        'min_stock' => 5.000,
        'unit' => 'kg',
        'cost_per_unit' => 45000,
        'expires_at' => null,
      ],

      // LOW STOCK ITEMS (untuk testing alert)
      [
        'name' => 'Oregano Kering',
        'stock' => 0,
        'min_stock' => 2.000,
        'unit' => 'kg',
        'cost_per_unit' => 250000,
        'expires_at' => null,
      ],
      [
        'name' => 'Kemangi Segar',
        'stock' => 0,
        'min_stock' => 2.000,
        'unit' => 'kg',
        'cost_per_unit' => 35000,
        'expires_at' => null,
      ],
    ];

    foreach ($inventories as $inventory) {
      Inventory::create($inventory);
    }

    $this->command->info('✓ Created ' . count($inventories) . ' inventory items');
  }
}
