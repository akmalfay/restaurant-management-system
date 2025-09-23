<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','table_id', 'staff_id','type', 'total', 'status', 'order_date'];

    public function orderItems(){
      return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function loyaltyPoints(){
      return $this->hasMany(LoyaltyPoint::class, 'order_id');
    }

    public function customer(){
      return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function table(){
      return $this->belongsTo(Table::class, 'table_id');
    }

    public function staff(){
      return $this->belongsTo(Staff::class, 'staff_id');
    }
}
