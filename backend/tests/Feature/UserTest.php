<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_profile()
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->postJson('/api/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);
    }

    public function test_guest_cannot_fetch_profile()
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }

    public function test_order_updates_user_address_fields()
    {
        $user = User::factory()->create([
            'email' => 'orderupdate@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'sanctum');

        $product = \App\Models\Product::factory()->create();

        $orderPayload = [
            'name' => 'Order User',
            'email' => 'orderupdate@example.com',
            'delivery_address' => 'Példa utca 1.',
            'delivery_city' => 'Budapest',
            'delivery_postal_code' => '1111',
            'billing_address' => 'Példa utca 1.',
            'billing_city' => 'Budapest',
            'billing_postal_code' => '1111',
            'phone' => '+36701234567',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1]
            ]
        ];

        $response = $this->postJson('/api/orders', $orderPayload);
        $response->assertStatus(201);

        $user->refresh();
        $this->assertEquals('Példa utca 1.', $user->delivery_address);
        $this->assertEquals('Budapest', $user->delivery_city);
        $this->assertEquals('1111', $user->delivery_postal_code);
        $this->assertEquals('+36701234567', $user->phone);
    }
}