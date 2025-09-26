<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_place_order_with_valid_payload()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'delivery_address' => 'Budapest utca 1',
            'delivery_city' => 'Budapest',
            'delivery_postal_code' => '1111',
            'billing_address' => 'Budapest utca 1',
            'billing_city' => 'Budapest',
            'billing_postal_code' => '1111',
            'phone' => '+36201234567',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);
        $response->assertStatus(201);
        $response->assertJsonFragment(['status' => 'pending']);
    }

    public function test_order_fails_with_invalid_payload()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => '',
            'email' => 'not-an-email',
            'delivery_address' => '',
            'delivery_city' => '',
            'delivery_postal_code' => 'bad',
            'billing_address' => '',
            'billing_city' => '',
            'billing_postal_code' => 'bad',
            'phone' => 'notaphone',
            'items' => []
        ];

        $response = $this->postJson('/api/orders', $payload);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_order_items_are_saved_and_linked_to_products()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'delivery_address' => 'Budapest utca 1',
            'delivery_city' => 'Budapest',
            'delivery_postal_code' => '1111',
            'billing_address' => 'Budapest utca 1',
            'billing_city' => 'Budapest',
            'billing_postal_code' => '1111',
            'phone' => '+36201234567',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);
        $response->assertStatus(201);

        $orderId = $response->json('id');
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_authenticated_user_can_list_their_orders()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'delivery_address' => 'Budapest utca 1',
            'delivery_city' => 'Budapest',
            'delivery_postal_code' => '1111',
            'billing_address' => 'Budapest utca 1',
            'billing_city' => 'Budapest',
            'billing_postal_code' => '1111',
            'phone' => '+36201234567',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ];

        $this->postJson('/api/orders', $payload);

        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure([['id', 'items']]);
    }

    public function test_guest_cannot_list_orders()
    {
        $response = $this->getJson('/api/orders');
        $response->assertStatus(401);
    }
}