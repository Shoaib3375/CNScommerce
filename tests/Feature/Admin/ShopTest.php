<?php

namespace Tests\Feature\Admin;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->owner = User::factory()->shopOwner()->create();
    }

    public function test_admin_can_create_a_shop(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/shops', [
            'name'        => 'Test Shop',
            'description' => 'A test shop',
            'status'      => true,
            'owner_id'    => $this->owner->id,
        ]);

        $response->assertRedirect('/admin/shops');
        $this->assertDatabaseHas('shops', ['name' => 'Test Shop']);
    }

    public function test_admin_can_update_a_shop(): void
    {
        $shop = Shop::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin)->patch("/admin/shops/{$shop->slug}", [
            'name'        => 'Updated Shop Name',
            'description' => $shop->description,
            'status'      => true,
            'owner_id'    => $this->owner->id,
        ]);

        $response->assertRedirect('/admin/shops');
        $this->assertDatabaseHas('shops', ['name' => 'Updated Shop Name']);
    }

    public function test_admin_can_delete_a_shop(): void
    {
        $shop = Shop::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin)->delete("/admin/shops/{$shop->slug}");

        $response->assertRedirect('/admin/shops');
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
    }

    public function test_shop_owner_cannot_create_a_shop_via_admin_routes(): void
    {
        $response = $this->actingAs($this->owner)->post('/admin/shops', [
            'name'     => 'Unauthorized Shop',
            'status'   => true,
            'owner_id' => $this->owner->id,
        ]);

        $response->assertStatus(403);
    }
}
