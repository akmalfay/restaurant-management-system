<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
  protected $fillable = ['inventory_id', 'batch_id', 'type', 'quantity', 'notes'];

  protected $casts = [
    'quantity' => 'float',
  ];

  public function inventory()
  {
    return $this->belongsTo(Inventory::class);
  }

  public function batch()
  {
    return $this->belongsTo(InventoryBatch::class, 'batch_id');
  }
}
