<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
  protected $fillable = [
    'customer_id',
    'order_id',
    'points',
    'type',
    'description',
  ];

  protected $casts = [
    'created_at' => 'datetime',
  ];

  public function customer()
  {
    return $this->belongsTo(CustomerDetail::class, 'customer_id');
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
