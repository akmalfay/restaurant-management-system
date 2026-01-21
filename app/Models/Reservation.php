<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
  use HasFactory;

  protected $fillable = [
    'table_id',
    'reservation_date',
    'start_time',
    'end_time',
    'status',
  ];

  protected $casts = [
    'reservation_date' => 'date',
    'start_time' => 'datetime:H:i:s',
    'end_time' => 'datetime:H:i:s',
  ];

  // Relasi ke Orders (satu reservasi bisa punya banyak orders)
  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  // Relasi ke Table
  public function table()
  {
    return $this->belongsTo(Table::class);
  }
}
