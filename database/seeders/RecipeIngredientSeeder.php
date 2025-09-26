<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\RecipeIngredient;
use Illuminate\Database\Seeder;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            // Nasi Goreng
            [
                ["menu_id" => 1, "inventory_id" => 1, "quantity" => 0.2], // Rice
                ["menu_id" => 1, "inventory_id" => 5, "quantity" => 2], // Eggs
                ["menu_id" => 1, "inventory_id" => 6, "quantity" => 0.05], // Cooking Oil
                ["menu_id" => 1, "inventory_id" => 7, "quantity" => 0.02], // Soy Sauce
                ["menu_id" => 1, "inventory_id" => 9, "quantity" => 0.01], // Chili
                ["menu_id" => 1, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 1, "inventory_id" => 11, "quantity" => 0.01], // Shallot
            ],
            // Sate Ayam
            [
                ["menu_id" => 2, "inventory_id" => 2, "quantity" => 0.15], // Chicken
                ["menu_id" => 2, "inventory_id" => 6, "quantity" => 0.02], // Cooking Oil
                ["menu_id" => 2, "inventory_id" => 8, "quantity" => 0.02], // Sweet Soy Sauce
                ["menu_id" => 2, "inventory_id" => 9, "quantity" => 0.01], // Chili
                ["menu_id" => 2, "inventory_id" => 10, "quantity" => 0.005], // Garlic
            ],
            // Beef Steak
            [
                ["menu_id" => 3, "inventory_id" => 3, "quantity" => 0.25], // Beef
                ["menu_id" => 3, "inventory_id" => 6, "quantity" => 0.02], // Cooking Oil
                ["menu_id" => 3, "inventory_id" => 14, "quantity" => 0.15], // Potato
                ["menu_id" => 3, "inventory_id" => 13, "quantity" => 0.05], // Carrot
                ["menu_id" => 3, "inventory_id" => 12, "quantity" => 0.05], // Cabbage
                ["menu_id" => 3, "inventory_id" => 10, "quantity" => 0.01], // Garlic
            ],
            // Ikan Bakar
            [
                ["menu_id" => 4, "inventory_id" => 4, "quantity" => 0.3], // Fish
                ["menu_id" => 4, "inventory_id" => 6, "quantity" => 0.02], // Cooking Oil
                ["menu_id" => 4, "inventory_id" => 8, "quantity" => 0.02], // Sweet Soy Sauce
                ["menu_id" => 4, "inventory_id" => 9, "quantity" => 0.01], // Chili
                ["menu_id" => 4, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 4, "inventory_id" => 11, "quantity" => 0.01], // Shallot
            ],
            // Capcay
            [
                ["menu_id" => 5, "inventory_id" => 12, "quantity" => 0.1], // Cabbage
                ["menu_id" => 5, "inventory_id" => 13, "quantity" => 0.05], // Carrot
                ["menu_id" => 5, "inventory_id" => 14, "quantity" => 0.05], // Potato
                ["menu_id" => 5, "inventory_id" => 15, "quantity" => 2], // Tofu
                ["menu_id" => 5, "inventory_id" => 16, "quantity" => 2], // Tempeh
                ["menu_id" => 5, "inventory_id" => 9, "quantity" => 0.01], // Chili
                ["menu_id" => 5, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 5, "inventory_id" => 11, "quantity" => 0.01], // Shallot
                ["menu_id" => 5, "inventory_id" => 6, "quantity" => 0.03], // Cooking Oil
            ],
            // Tahu Goreng
            [
                ["menu_id" => 6, "inventory_id" => 15, "quantity" => 2], // Tofu
                ["menu_id" => 6, "inventory_id" => 6, "quantity" => 0.05], // Cooking Oil
                ["menu_id" => 6, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 6, "inventory_id" => 9, "quantity" => 0.005], // Chili
            ],

            // Tempe Goreng
            [
                ["menu_id" => 7, "inventory_id" => 16, "quantity" => 2], // Tempeh
                ["menu_id" => 7, "inventory_id" => 6, "quantity" => 0.05], // Cooking Oil
                ["menu_id" => 7, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 7, "inventory_id" => 9, "quantity" => 0.005], // Chili
            ],
            // Mie Goreng
            [
                ["menu_id" => 8, "inventory_id" => 17, "quantity" => 1], // Instant Noodles
                ["menu_id" => 8, "inventory_id" => 5, "quantity" => 1], // Eggs
                ["menu_id" => 8, "inventory_id" => 6, "quantity" => 0.02], // Cooking Oil
                ["menu_id" => 8, "inventory_id" => 10, "quantity" => 0.01], // Garlic
                ["menu_id" => 8, "inventory_id" => 11, "quantity" => 0.01], // Shallot
                ["menu_id" => 8, "inventory_id" => 7, "quantity" => 0.02], // Soy Sauce
                ["menu_id" => 8, "inventory_id" => 8, "quantity" => 0.02], // Sweet Soy Sauce
                ["menu_id" => 8, "inventory_id" => 12, "quantity" => 0.05], // Cabbage
                ["menu_id" => 8, "inventory_id" => 13, "quantity" => 0.03], // Carrot
            ],
            // Teh
            [
                ["menu_id" => 9, "inventory_id" => 19, "quantity" => 1], // Tea Bag
                ["menu_id" => 9, "inventory_id" => 18, "quantity" => 0.25], // Mineral Water
            ],
            // Kopi
            [
                ["menu_id" => 10, "inventory_id" => 20, "quantity" => 1], // Coffee Sachet
                ["menu_id" => 10, "inventory_id" => 18, "quantity" => 0.25], // Mineral Water
            ],
        ];

        foreach ($recipes as $recipe) {
            foreach ($recipe as $item) {
                RecipeIngredient::create($item);
            }
        }
    }
}
