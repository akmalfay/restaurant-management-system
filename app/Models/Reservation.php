<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
  protected $fillable = [
    'table_id',
    'order_id',
    'reservation_time',
    'session',
    'guests',
    'status',
  ];

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }

  public function table()
  {
    return $this->belongsTo(Table::class, 'table_id');
  }
}
