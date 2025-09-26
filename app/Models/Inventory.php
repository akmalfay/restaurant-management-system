<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        "name",
        "stock",
        "min_stock",
        "unit",
        "item_code",
        "cost_per_unit",
        "expires_at",
    ];

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, "inventory_id");
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, "inventory_id");
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->item_code)) {
                $lastItem = self::orderBy("id", "desc")->first();

                if ($lastItem && $lastItem->item_code) {
                    $lastNumber = (int) substr($lastItem->item_code, -5);
                    $newNumber = str_pad($lastNumber + 1, 5, "0", STR_PAD_LEFT);
                } else {
                    $newNumber = "00001";
                }

                $model->item_code = "ITEM-" . $newNumber;
            }
        });
    }
}
