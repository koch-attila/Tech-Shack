<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Smartphones',
            'Laptops',
            'Tablets',
            'Headphones',
            'Wearables',
            'Cameras',
            'Gaming Consoles',
            'Monitors',
            'PC Components',
            'Accessories',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
