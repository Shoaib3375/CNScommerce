<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_create_a_shop_owner(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/owners', [
            'name'                  => 'New Owner',
            'email'                 => 'newowner@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/owners');
        $this->assertDatabaseHas('users', [
            'email' => 'newowner@example.com',
            'role'  => User::ROLE_SHOP_OWNER,
        ]);
    }

    public function test_admin_can_update_a_shop_owner(): void
    {
        $owner = User::factory()->shopOwner()->create([
            'name'  => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/owners/{$owner->id}", [
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect('/admin/owners');
        $this->assertDatabaseHas('users', [
            'id'    => $owner->id,
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_register_route_returns_404(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }
}
