<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('categories')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        if (isset($validated['categories'])) {
            $product->categories()->attach($validated['categories']);
        }

        return response()->json($product->load('categories'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product->load('categories');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update($validated);

        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return $product->load('categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }

    public function ratings(Product $product)
    {
        return response()->json($product->ratings()->with('user')->get());
    }

    public function categories(Product $product)
    {
        return response()->json($product->categories);
    }
}
