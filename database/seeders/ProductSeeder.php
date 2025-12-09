<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fruits
        Product::create([
            'name' => 'Fresh Mango',
            'branch' => 'Campus Farm',
            'category' => 'Fruits',
            'price' => 25.00,
            'stock' => 50,
            'unit' => 'piece',
            'status' => 'Available',
            'image' => '/images/mango.jpg',
        ]);

        Product::create([
            'name' => 'Sweet Watermelon',
            'branch' => 'Campus Farm',
            'category' => 'Fruits',
            'price' => 45.00,
            'stock' => 30,
            'unit' => 'piece',
            'status' => 'Available',
            'image' => '/images/watermelon.jpg',
        ]);

        // Vegetables
        Product::create([
            'name' => 'Fresh Cucumber',
            'branch' => 'Campus Farm',
            'category' => 'Vegetables',
            'price' => 15.00,
            'stock' => 40,
            'unit' => 'piece',
            'status' => 'Available',
            'image' => '/images/cucumber.jpg',
        ]);

        // Poultry
        Product::create([
            'name' => 'Fresh Chicken Eggs',
            'branch' => 'Campus Farm',
            'category' => 'Poultry',
            'price' => 8.00,
            'stock' => 100,
            'unit' => 'piece',
            'status' => 'Available',
            'image' => '/images/egg.jpg',
        ]);
    }
}
