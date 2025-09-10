<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'iPhone 15 Pro', 'description' => 'Apple flagship smartphone', 'price' => 1199, 'brand' => 'Apple', 'image' => 'iphone15pro.jpg', 'category' => 'Smartphones'],
            ['name' => 'Samsung Galaxy S23', 'description' => 'Flagship Samsung Galaxy smartphone', 'price' => 999, 'brand' => 'Samsung', 'image' => 'galaxys23.jpg', 'category' => 'Smartphones'],
            ['name' => 'MacBook Pro 14"', 'description' => 'Powerful laptop with M3 chip', 'price' => 1999, 'brand' => 'Apple', 'image' => 'macbookpro14.png', 'category' => 'Laptops'],
            ['name' => 'Dell XPS 13', 'description' => 'Ultra-portable Windows laptop', 'price' => 1499, 'brand' => 'Dell', 'image' => 'dellxps13.jpg', 'category' => 'Laptops'],
            ['name' => 'iPad Pro 12.9"', 'description' => 'High-end Apple tablet', 'price' => 1299, 'brand' => 'Apple', 'image' => 'ipadpro.jpg', 'category' => 'Tablets'],
            ['name' => 'Samsung Galaxy Tab S9', 'description' => 'Premium Android tablet', 'price' => 899, 'brand' => 'Samsung', 'image' => 'tabs9.jpg', 'category' => 'Tablets'],
            ['name' => 'Sony WH-1000XM5', 'description' => 'Noise cancelling headphones', 'price' => 399, 'brand' => 'Sony', 'image' => 'wh1000xm5.jpg', 'category' => 'Headphones'],
            ['name' => 'Bose QuietComfort 45', 'description' => 'Premium noise cancelling headphones', 'price' => 329, 'brand' => 'Bose', 'image' => 'qc45.jpg', 'category' => 'Headphones'],
            ['name' => 'Apple Watch Ultra 2', 'description' => 'Rugged smartwatch for athletes', 'price' => 799, 'brand' => 'Apple', 'image' => 'watchultra2.jpg', 'category' => 'Wearables'],
            ['name' => 'Samsung Galaxy Watch 6', 'description' => 'Smartwatch with health tracking', 'price' => 349, 'brand' => 'Samsung', 'image' => 'galaxywatch6.jpg', 'category' => 'Wearables'],
            ['name' => 'Canon EOS R6 Mark II', 'description' => 'Full-frame mirrorless camera', 'price' => 2499, 'brand' => 'Canon', 'image' => 'eosr6.jpg', 'category' => 'Cameras'],
            ['name' => 'Sony Alpha A7 IV', 'description' => 'High-performance mirrorless camera', 'price' => 2399, 'brand' => 'Sony', 'image' => 'a7iv.jpg', 'category' => 'Cameras'],
            ['name' => 'PlayStation 5', 'description' => 'Sony gaming console', 'price' => 499, 'brand' => 'Sony', 'image' => 'ps5.jpg', 'category' => 'Gaming Consoles'],
            ['name' => 'Xbox Series X', 'description' => 'Microsoft gaming console', 'price' => 499, 'brand' => 'Microsoft', 'image' => 'xboxx.jpg', 'category' => 'Gaming Consoles'],
            ['name' => 'LG Ultragear 27"', 'description' => 'Gaming monitor with 144Hz refresh rate', 'price' => 399, 'brand' => 'LG', 'image' => 'ultragear27.jpg', 'category' => 'Monitors'],
            ['name' => 'NVIDIA RTX 4090', 'description' => 'High-end graphics card', 'price' => 1599, 'brand' => 'NVIDIA', 'image' => 'rtx4090.jpg', 'category' => 'PC Components'],
            ['name' => 'Logitech MX Master 3S', 'description' => 'Premium productivity mouse', 'price' => 99, 'brand' => 'Logitech', 'image' => 'mxmaster3s.jpg', 'category' => 'Accessories'],
            ['name' => 'Anker PowerCore 20000', 'description' => 'High-capacity portable charger', 'price' => 59, 'brand' => 'Anker', 'image' => 'powercore20000.jpg', 'category' => 'Accessories'],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();
            $product = Product::create(collect($data)->except('category')->toArray());
            $product->categories()->attach($category);
        }
    }
}
