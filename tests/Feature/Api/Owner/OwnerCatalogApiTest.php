<?php

namespace Tests\Feature\Api\Owner;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerCatalogApiTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Shop $shop;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->shopOwner()->create();
        $this->shop  = Shop::factory()->create(['owner_id' => $this->owner->id]);
    }

    public function test_owner_can_browse_catalog_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->count(5)->create(['created_by' => $admin->id]);

        $response = $this->actingAs($this->owner)->getJson('/api/owner/catalog');

        // Response shape: {products: {current_page, data, ...}, shop, assignedProductIds, search}
        $response->assertOk()
            ->assertJsonStructure(['products', 'shop', 'assignedProductIds']);
    }

    public function test_owner_can_assign_product_to_shop_via_api(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->owner)
            ->postJson("/api/owner/catalog/assign/{$product->slug}");

        $response->assertCreated();

        $this->assertDatabaseHas('shop_products', [
            'shop_id'    => $this->shop->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_duplicate_assignment_returns_validation_error(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->owner)
            ->postJson("/api/owner/catalog/assign/{$product->slug}");

        $response = $this->actingAs($this->owner)
            ->postJson("/api/owner/catalog/assign/{$product->slug}");

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('product_id');
    }

    public function test_shopless_owner_gets_422_not_500(): void
    {
        $shoplessOwner = User::factory()->shopOwner()->create();
        $product       = Product::factory()->create();

        $response = $this->actingAs($shoplessOwner)
            ->postJson("/api/owner/catalog/assign/{$product->slug}");

        $response->assertUnprocessable();
    }

    public function test_owner_can_list_assigned_products(): void
    {
        $product = Product::factory()->create();
        $this->shop->products()->attach($product->id, [
            'price' => 29.99, 'description' => null, 'thumbnail' => null, 'is_active' => true,
        ]);

        $response = $this->actingAs($this->owner)->getJson('/api/owner/shop-products');

        // Returns {shopProducts: {current_page, data, ...}}
        $response->assertOk()
            ->assertJsonStructure(['shopProducts' => ['current_page', 'data', 'total']]);
    }

    public function test_catalog_search_filters_products(): void
    {
        Product::factory()->create(['name' => 'Laptop Stand Pro']);
        Product::factory()->create(['name' => 'USB Hub']);

        $response = $this->actingAs($this->owner)->getJson('/api/owner/catalog?search=Laptop');

        $response->assertOk();
        // products is nested paginator
        $names = array_column($response->json('products.data'), 'name');
        $this->assertContains('Laptop Stand Pro', $names);
        $this->assertNotContains('USB Hub', $names);
    }
}
