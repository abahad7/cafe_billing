<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = [
            'Fresh Juices' => [
                ['name' => 'Orange Juice', 'price' => 60],
                ['name' => 'Watermelon Juice', 'price' => 50],
                ['name' => 'Pineapple Juice', 'price' => 60],
                ['name' => 'Mixed Fruit Juice', 'price' => 70],
            ],
            'Waffles' => [
                ['name' => 'Belgian Chocolate Waffle', 'price' => 120],
                ['name' => 'Nutella Banana Waffle', 'price' => 140],
                ['name' => 'Honey Butter Waffle', 'price' => 100],
            ],
            'Tea & Coffee' => [
                ['name' => 'Masala Chai', 'price' => 25],
                ['name' => 'Filter Coffee', 'price' => 35],
                ['name' => 'Cold Coffee', 'price' => 80],
            ],
            'Snacks' => [
                ['name' => 'French Fries', 'price' => 80],
                ['name' => 'Cheese Sandwich', 'price' => 70],
                ['name' => 'Veg Burger', 'price' => 90],
            ],
        ];

        foreach ($menu as $categoryName => $items) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($items as $item) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                ]);
            }
        }
    }
}