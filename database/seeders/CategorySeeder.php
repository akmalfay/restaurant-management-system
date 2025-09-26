<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "Indonesian Dishes",
                "description" =>
                    "Traditional Indonesian main courses such as nasi goreng, rendang, and soto.",
            ],
            [
                "name" => "Rice & Noodles",
                "description" =>
                    "Meals based on rice or noodles, including fried rice, fried noodles, and rice bowls.",
            ],
            [
                "name" => "Snacks",
                "description" =>
                    "Light Indonesian snacks like gorengan, satay, or pempek.",
            ],
            [
                "name" => "Soups",
                "description" =>
                    "Warm dishes such as soto, bakso, or chicken soup.",
            ],
            [
                "name" => "Vegetarian Dishes",
                "description" =>
                    "Vegetable-based Indonesian meals like gado-gado or urap.",
            ],
            [
                "name" => "Desserts",
                "description" =>
                    "Indonesian sweets such as es cendol, klepon, or pisang goreng with toppings.",
            ],
            [
                "name" => "Beverages",
                "description" =>
                    "Various drinks including iced tea, coffee, juice, and traditional drinks like wedang jahe.",
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
