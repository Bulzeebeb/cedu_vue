<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Rental',
                'description' => 'Rental spaces with monthly rates',
                'is_active' => true
            ],
            [
                'name' => 'Hostel',
                'description' => 'Dorm-style rooms for students',
                'is_active' => true
            ],
            [
                'name' => 'Commercial',
                'description' => 'Commercial facilities like halls and shops',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
