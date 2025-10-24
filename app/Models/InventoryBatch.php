<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model
{
    protected $fillable = ['inventory_id', 'quantity', 'expires_at', 'cost_per_unit'];

    protected $casts = [
        'quantity' => 'float',
        'expires_at' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, "inventory_id");
    }
}
