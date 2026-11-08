<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    private Shop $activeShop;
    private Shop $inactiveShop;
    private Product $assignedActiveProduct;
    private Product $unassignedProduct;
    private Product $assignedInactiveProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $owner = User::factory()->shopOwner()->create();
        $admin = User::factory()->admin()->create();

        $this->activeShop   = Shop::factory()->create(['owner_id' => $owner->id, 'status' => true]);
        $this->inactiveShop = Shop::factory()->create(['owner_id' => $owner->id, 'status' => false]);

        $this->assignedActiveProduct   = Product::factory()->create(['created_by' => $admin->id]);
        $this->unassignedProduct       = Product::factory()->create(['created_by' => $admin->id]);
        $this->assignedInactiveProduct = Product::factory()->create(['created_by' => $admin->id]);

        // Assign active product to the active shop
        $this->activeShop->products()->attach($this->assignedActiveProduct->id, [
            'price'       => 29.99,
            'description' => null,
            'thumbnail'   => null,
            'is_active'   => true,
        ]);

        // Assign inactive product to the active shop (is_active = false)
        $this->activeShop->products()->attach($this->assignedInactiveProduct->id, [
            'price'       => 19.99,
            'description' => null,
            'thumbnail'   => null,
            'is_active'   => false,
        ]);
    }

    public function test_active_assigned_product_appears_in_shop_show_response(): void
    {
        $response = $this->get("/shop/{$this->activeShop->slug}");

        $response->assertStatus(200);

        $products = $response->viewData('page')['props']['products']['data'] ?? [];
        $productIds = array_column($products, 'id');

        $this->assertContains($this->assignedActiveProduct->id, $productIds);
    }

    public function test_unassigned_product_does_not_appear_in_shop(): void
    {
        $response = $this->get("/shop/{$this->activeShop->slug}");

        $response->assertStatus(200);

        $products = $response->viewData('page')['props']['products']['data'] ?? [];
        $productIds = array_column($products, 'id');

        $this->assertNotContains($this->unassignedProduct->id, $productIds);
    }

    public function test_inactive_assigned_product_does_not_appear_in_shop(): void
    {
        $response = $this->get("/shop/{$this->activeShop->slug}");

        $response->assertStatus(200);

        $products = $response->viewData('page')['props']['products']['data'] ?? [];
        $productIds = array_column($products, 'id');

        $this->assertNotContains($this->assignedInactiveProduct->id, $productIds);
    }

    public function test_product_detail_returns_404_when_not_assigned_to_shop(): void
    {
        $response = $this->get(
            "/shop/{$this->activeShop->slug}/products/{$this->unassignedProduct->slug}"
        );

        $response->assertStatus(404);
    }

    public function test_product_detail_returns_200_when_assigned_and_active(): void
    {
        $response = $this->get(
            "/shop/{$this->activeShop->slug}/products/{$this->assignedActiveProduct->slug}"
        );

        $response->assertStatus(200);
    }

    public function test_inactive_shop_returns_404(): void
    {
        $response = $this->get("/shop/{$this->inactiveShop->slug}");

        $response->assertStatus(404);
    }
}
