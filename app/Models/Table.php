<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  protected $fillable = ['name', 'status', 'capacity'];

  public function reservations()
  {
    return $this->hasMany(Reservation::class, 'table_id');
  }

  /**
   * Get capacity based on category prefix
   */
  public static function getCapacityByCategory(string $category): int
  {
    return match ($category) {
      'VIP' => 4,
      'Terrace' => 4,
      'Outdoor' => 3,
      'Indoor' => 2,
      default => 2,
    };
  }

  /**
   * Get category from table name
   */
  public function getCategoryAttribute(): string
  {
    return explode('-', $this->name)[0] ?? 'Indoor';
  }
}
