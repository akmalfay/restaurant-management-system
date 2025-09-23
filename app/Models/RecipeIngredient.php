<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $fillable = ['quantity'];

    public function menuItem(){
      return $this->belongsTo(MenuItem::class, 'menu_id');
    }

    public function inventory(){
      return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
