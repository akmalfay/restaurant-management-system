<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
  protected $fillable = ['customer_id', 'order_id', 'points', 'type'];

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }

  public function customers()
  {
    return $this->hasMany(CustomerDetail::class, 'customer_id');
  }
}
