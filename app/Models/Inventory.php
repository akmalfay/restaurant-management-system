<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

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

  protected $casts = [
    'stock' => 'decimal:3',
    'min_stock' => 'decimal:3',
    'cost_per_unit' => 'decimal:2',
    'expires_at' => 'date',
  ];

  protected $appends = ['total_stock', 'next_expiry'];

  public function stockMovements()
  {
    return $this->hasMany(StockMovement::class, 'inventory_id');
  }

  // [TAMBAH] relasi ke inventory_batches
  public function batches()
  {
    return $this->hasMany(InventoryBatch::class, 'inventory_id');
  }

  // Stok dihitung dari total batch (tanpa ubah kolom existing)
  public function getTotalStockAttribute(): float
  {
    if (!Schema::hasTable('inventory_batches')) {
      return (float) ($this->attributes['stock'] ?? 0);
    }
    return (float) ($this->batches()->sum('quantity') ?? 0);
  }

  // Exp terdekat dari batch (fallback ke kolom expires_at jika belum migrasi penuh)
  public function getNextExpiryAttribute()
  {
    if (Schema::hasTable('inventory_batches')) {
      $fromBatch = $this->batches()->whereNotNull('expires_at')->orderBy('expires_at')->value('expires_at');
      if ($fromBatch) return $fromBatch;
    }
    return $this->attributes['expires_at'] ?? null;
  }

  public function isExpired(): bool
  {
    $d = $this->next_expiry;
    return $d ? now()->greaterThan(Carbon::parse($d)) : false;
  }

  public function isNearExpiry(): bool
  {
    $d = $this->next_expiry;
    if (!$d) return false;
    $days = now()->diffInDays(Carbon::parse($d), false);
    return $days >= 0 && $days <= 7;
  }

  public function isSafe(): bool
  {
    $d = $this->next_expiry;
    if (!$d) return false;
    $days = now()->diffInDays(Carbon::parse($d), false);
    return $days > 7;
  }

  public function isLowStock(): bool
  {
    $stock = $this->total_stock;
    $min   = (float) ($this->min_stock ?? 0);
    return $stock <= $min;
  }
}
