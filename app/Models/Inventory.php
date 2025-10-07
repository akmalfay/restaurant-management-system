<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
  protected $fillable = [
    'name',
    'stock',
    'min_stock',
    'unit',
    'cost_per_unit',
    'expires_at',
  ];

  public function stockMovements()
  {
    return $this->hasMany(StockMovement::class, 'inventory_id');
  }
}
