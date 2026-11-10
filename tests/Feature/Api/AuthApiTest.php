<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_via_api(): void
    {
        $user = User::factory()->admin()->create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['id', 'name', 'email', 'role']);

        $this->assertEquals('admin', $response->json('role'));
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->admin()->create(['email' => 'admin@example.com']);

        $response = $this->postJson('/api/login', [
            'email'    => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_endpoint_returns_user(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->getJson('/api/user');

        $response->assertOk()
            ->assertJsonStructure(['id', 'name', 'email', 'role']);
    }

    public function test_unauthenticated_request_to_user_endpoint_returns_401(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

    public function test_user_can_logout_via_api(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson('/api/logout');

        $response->assertOk();
    }

    public function test_guest_cannot_access_admin_api(): void
    {
        $this->getJson('/api/admin/dashboard')->assertUnauthorized();
    }

    public function test_guest_cannot_access_owner_api(): void
    {
        $this->getJson('/api/owner/dashboard')->assertUnauthorized();
    }

    public function test_owner_cannot_access_admin_api(): void
    {
        $owner = User::factory()->shopOwner()->create();

        $this->actingAs($owner)->getJson('/api/admin/dashboard')->assertForbidden();
    }

    public function test_admin_cannot_access_owner_api(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->getJson('/api/owner/dashboard')->assertForbidden();
    }
}
