<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['category_id','name', 'price','available'];

    public function category(){
      return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems(){
      return $this->hasMany(OrderItem::class, 'menu_id');
    }

    public function recipeIngredients(){
      return $this->hasMany(RecipeIngredient::class, 'menu_id');
    }
}
