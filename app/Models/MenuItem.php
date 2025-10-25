<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
  protected $fillable = [
    'name',
    'image',
    'description',
    'price',
    'is_available',
    'category_id',
  ];

  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function orderItems()
  {
    return $this->hasMany(OrderItem::class, 'menu_id');
  }
}
