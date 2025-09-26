<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return Order::with('items.product')->where('user_id', $user->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $request->validated();

        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_postal_code' => $request->billing_postal_code,
            'delivery_address' => $request->delivery_address,
            'delivery_city' => $request->delivery_city,
            'delivery_postal_code' => $request->delivery_postal_code,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->delivery_address = $request->delivery_address;
            $user->delivery_city = $request->delivery_city;
            $user->delivery_postal_code = $request->delivery_postal_code;
            $user->billing_address = $request->billing_address;
            $user->billing_city = $request->billing_city;
            $user->billing_postal_code = $request->billing_postal_code;
            $user->phone = $request->phone;
            $user->save();
        }

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
