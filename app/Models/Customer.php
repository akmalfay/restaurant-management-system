<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'points'];

    public function loyaltyPoints(){
      return $this->hasMany(LoyaltyPoint::class, 'customer_id');
    }

    public function reservations(){
      return $this->hasMany(Reservation::class, 'customer_id');
    }

    public function orders(){
      return $this->hasMany(Order::class, 'customer_id');
    }
}
