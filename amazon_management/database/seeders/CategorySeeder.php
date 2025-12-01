<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Clothing', 'Snacks', 'Cosmetics', 'Books', 'Apparel',
            'Pet Supplies', 'Toys', 'Skincare', 'Grocery', 'Outdoors',
            'Kitchen', 'Shoes'
        ];
        
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
} 