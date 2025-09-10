<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'completed',
                'billing_address' => '123 Tech Street',
                'delivery_address' => '123 Tech Street',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($products->random(3) as $product) {
                $qty = rand(1, 2);
                $total += $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
            }

            $order->update([
                'total' => $total,
            ]);
        }
    }
}

