<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
    'customer_id',
    'type',
    'total',
    'status',
    'points_redeemed',
    'order_time',
  ];

  protected $casts = [
    'order_time' => 'datetime',
    'total' => 'decimal:2',
  ];

  // Relasi ke CustomerDetail
  public function customer()
  {
    return $this->belongsTo(CustomerDetail::class, 'customer_id');
  }

  // Relasi ke OrderItems
  public function orderItems()
  {
    return $this->hasMany(OrderItem::class);
  }

  // Relasi ke Reservation (1 order bisa punya 1 reservasi)
  public function reservation()
  {
    return $this->hasOne(Reservation::class);
  }
}
