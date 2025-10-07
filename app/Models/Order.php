<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'customer_id',
    'type',
    'total',
    'status',
    'order_time',
    'points_redeemed',
  ];

  public function orderItems()
  {
    return $this->hasMany(OrderItem::class, 'order_id');
  }

  public function loyaltyPoints()
  {
    return $this->belongsTo(LoyaltyPoint::class, 'order_id');
  }

  public function customerDetail()
  {
    return $this->belongsTo(CustomerDetail::class, 'customer_id');
  }

  public function reservation()
  {
    return $this->belongsTo(Reservation::class, 'order_id');
  }
}
