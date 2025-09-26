<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ["order_id", "menu_id", "quantity", "price"];

    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, "menu_id");
    }
}
