<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_endpoints()
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);

        $response = $this->getJson('/api/orders');
        $response->assertStatus(401);
    }

    public function test_non_admin_cannot_access_admin_endpoints()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/admin/orders');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_endpoints()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson('/api/admin/orders');
        $response->assertStatus(200);
    }
}