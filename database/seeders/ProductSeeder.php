<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

//public function run()
{
    Product::create([
        'title' => 'Fresh Broccoli',
        'quantity' => 20,
        'price' => 99.00,
        'metric' => 'kilo',
        'image' => 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80',
    ]);
    // Add more products if needed
}
