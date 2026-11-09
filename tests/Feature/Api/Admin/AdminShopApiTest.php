<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminShopApiTest extends TestCase
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

    public function test_admin_can_list_shops(): void
    {
        Shop::factory()->count(3)->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/shops');

        $response->assertOk()
            ->assertJsonStructure(['current_page', 'data', 'last_page', 'total']);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_admin_can_create_shop_via_api(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/shops', [
            'name'        => 'API Test Shop',
            'description' => 'Created via API',
            'status'      => true,
            'owner_id'    => $this->owner->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'API Test Shop');

        $this->assertDatabaseHas('shops', ['name' => 'API Test Shop']);
    }

    public function test_admin_can_update_shop_via_api(): void
    {
        $shop = Shop::factory()->create(['owner_id' => $this->owner->id]);

        // Shop model binds by slug
        $response = $this->actingAs($this->admin)->putJson("/api/admin/shops/{$shop->slug}", [
            'name'        => 'Updated via API',
            'description' => '',
            'status'      => true,
            'owner_id'    => $this->owner->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'Updated via API');

        $this->assertDatabaseHas('shops', ['name' => 'Updated via API']);
    }

    public function test_admin_can_delete_shop_via_api(): void
    {
        $shop = Shop::factory()->create(['owner_id' => $this->owner->id]);

        // Shop model binds by slug
        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/shops/{$shop->slug}");

        // destroy returns 204 No Content
        $response->assertNoContent();
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
    }

    public function test_owner_cannot_access_admin_shops_api(): void
    {
        $this->actingAs($this->owner)->getJson('/api/admin/shops')->assertForbidden();
    }

    public function test_create_shop_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/shops', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'owner_id']);
    }

    public function test_admin_dashboard_returns_counts(): void
    {
        Shop::factory()->count(2)->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/dashboard');

        // Dashboard returns {stats: {shops, owners, products, activeAssignments}}
        $response->assertOk()
            ->assertJsonStructure(['stats' => ['shops', 'owners', 'products', 'activeAssignments']]);

        $this->assertGreaterThanOrEqual(2, $response->json('stats.shops'));
    }

    public function test_admin_shop_search_filters_by_name(): void
    {
        Shop::factory()->create(['name' => 'Alpha Store', 'owner_id' => $this->owner->id]);
        Shop::factory()->create(['name' => 'Beta Boutique', 'owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/shops?search=Alpha');

        $response->assertOk();
        $names = array_column($response->json('data'), 'name');
        $this->assertContains('Alpha Store', $names);
        $this->assertNotContains('Beta Boutique', $names);
    }
}
