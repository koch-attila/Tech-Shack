<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($products as $product) {
            foreach ($users as $user) {
                Rating::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'title' => "Review of {$product->name} by {$user->name}",
                    'comment' => 'Solid performance, would recommend.',
                    'rating' => rand(3, 5),
                ]);
            }
        }
    }
}
