<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Rating;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_products_returns_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_product_details_returns_product()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);

        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);
    }

    public function test_guest_can_access_products()
    {
        Product::factory()->create();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }

    public function test_product_has_categories()
    {
        $category = Category::factory()->create(['name' => 'Headphones']);
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        $response = $this->getJson("/api/products/{$product->id}/categories");
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Headphones']);
    }

    public function test_product_has_ratings()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $rating = Rating::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Excellent!',
        ]);

        $response = $this->getJson("/api/products/{$product->id}/ratings");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'rating' => 5,
            'comment' => 'Excellent!',
        ]);
    }
}