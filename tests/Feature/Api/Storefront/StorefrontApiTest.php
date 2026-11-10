<?php

namespace Tests\Feature\Api\Storefront;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontApiTest extends TestCase
{
    use RefreshDatabase;

    private Shop $activeShop;
    private Product $activeProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $owner = User::factory()->shopOwner()->create();
        $admin = User::factory()->admin()->create();

        $this->activeShop    = Shop::factory()->create(['owner_id' => $owner->id, 'status' => true]);
        $this->activeProduct = Product::factory()->create(['created_by' => $admin->id]);

        $this->activeShop->products()->attach($this->activeProduct->id, [
            'price' => '29.99', 'description' => null, 'thumbnail' => null, 'is_active' => true,
        ]);
    }

    public function test_storefront_home_returns_shops_and_featured(): void
    {
        $response = $this->getJson('/api/storefront/home');

        $response->assertOk()
            ->assertJsonStructure(['shops', 'featured']);
    }

    public function test_storefront_shop_returns_active_products(): void
    {
        $response = $this->getJson("/api/storefront/shops/{$this->activeShop->slug}");

        $response->assertOk()
            ->assertJsonStructure(['shop', 'products']);

        // products is a paginator: products.data
        $productIds = array_column($response->json('products.data'), 'id');
        $this->assertContains($this->activeProduct->id, $productIds);
    }

    public function test_storefront_does_not_show_unassigned_products(): void
    {
        $unassigned = Product::factory()->create();

        $response = $this->getJson("/api/storefront/shops/{$this->activeShop->slug}");

        $productIds = array_column($response->json('products.data'), 'id');
        $this->assertNotContains($unassigned->id, $productIds);
    }

    public function test_storefront_does_not_show_inactive_assignments(): void
    {
        $inactiveProduct = Product::factory()->create();
        $this->activeShop->products()->attach($inactiveProduct->id, [
            'price' => 9.99, 'description' => null, 'thumbnail' => null, 'is_active' => false,
        ]);

        $response = $this->getJson("/api/storefront/shops/{$this->activeShop->slug}");

        $productIds = array_column($response->json('products.data'), 'id');
        $this->assertNotContains($inactiveProduct->id, $productIds);
    }

    public function test_storefront_product_detail_returns_shop_specific_price(): void
    {
        $response = $this->getJson(
            "/api/storefront/shops/{$this->activeShop->slug}/products/{$this->activeProduct->slug}"
        );

        $response->assertOk()
            ->assertJsonStructure(['shop', 'product', 'shopProduct'])
            ->assertJsonPath('shopProduct.price', '29.99');
    }

    public function test_storefront_product_from_wrong_shop_returns_404(): void
    {
        $owner2 = User::factory()->shopOwner()->create();
        $shop2  = Shop::factory()->create(['owner_id' => $owner2->id, 'status' => true]);

        // activeProduct is NOT assigned to shop2
        $response = $this->getJson(
            "/api/storefront/shops/{$shop2->slug}/products/{$this->activeProduct->slug}"
        );

        $response->assertNotFound();
    }

    public function test_inactive_shop_returns_404(): void
    {
        $owner        = User::factory()->shopOwner()->create();
        $inactiveShop = Shop::factory()->create(['owner_id' => $owner->id, 'status' => false]);

        $this->getJson("/api/storefront/shops/{$inactiveShop->slug}")->assertNotFound();
    }

    public function test_live_search_returns_matching_products(): void
    {
        $response = $this->getJson('/api/search?q=' . urlencode(substr($this->activeProduct->name, 0, 5)));

        $response->assertOk();
        $this->assertIsArray($response->json());
    }

    public function test_live_search_returns_empty_for_short_query(): void
    {
        $response = $this->getJson('/api/search?q=a');

        $response->assertOk();
        $this->assertEmpty($response->json());
    }
}
