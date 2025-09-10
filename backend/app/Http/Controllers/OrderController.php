<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Order::with('items.product')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $request->validated();

        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = (int) $item['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);

            $total += (float) $product->price * $quantity;
        }

        $order->total = $total;
        $order->save();

        return response()->json($order->fresh()->load('items.product'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $order->load('items.product');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return $order->load('items.product');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
}
