<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['name', 'stock', 'min_stock', 'unit'];

    public function recipeIngredients(){
      return $this->hasMany(RecipeIngredient::class, 'inventory_id');
    }

    public function stockMovements(){
      return $this->hasMany(StockMovement::class, 'inventory_id');
    }
}
