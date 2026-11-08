<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_owner_dashboard(): void
    {
        $response = $this->get('/owner/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_shop_owner_gets_403_on_admin_dashboard(): void
    {
        $owner = User::factory()->shopOwner()->create();

        $response = $this->actingAs($owner)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_gets_403_on_owner_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/owner/dashboard');

        $response->assertStatus(403);
    }

    public function test_register_route_returns_404(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }
}
