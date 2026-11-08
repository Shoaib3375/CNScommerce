<?php

namespace Tests\Feature\Catalog;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    private User $ownerA;
    private User $ownerB;
    private Shop $shopA;
    private Product $productByB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ownerA = User::factory()->shopOwner()->create();
        $this->ownerB = User::factory()->shopOwner()->create();

        $this->shopA = Shop::factory()->create(['owner_id' => $this->ownerA->id]);
        Shop::factory()->create(['owner_id' => $this->ownerB->id]);

        // Product created by ownerB — ownerA should still see it in the catalog
        $this->productByB = Product::factory()->create(['created_by' => $this->ownerB->id]);

        // A second product for ownerA
        Product::factory()->create(['created_by' => $this->ownerA->id]);
    }

    public function test_owner_can_see_all_products_in_catalog_including_other_owners(): void
    {
        $response = $this->actingAs($this->ownerA)->get('/owner/catalog');

        $response->assertStatus(200);

        // Both products (one by ownerB) should be visible in the Inertia response
        $products = $response->viewData('page')['props']['products']['data'] ?? [];
        $productIds = array_column($products, 'id');

        $this->assertContains($this->productByB->id, $productIds);
    }

    public function test_owner_can_assign_a_product_to_their_shop(): void
    {
        $product = Product::factory()->create(['created_by' => $this->ownerA->id]);

        $response = $this->actingAs($this->ownerA)->post(
            "/owner/catalog/assign/{$product->slug}",
            ['product_id' => $product->id]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('shop_products', [
            'shop_id'    => $this->shopA->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_duplicate_assignment_is_rejected_with_validation_error(): void
    {
        $product = Product::factory()->create(['created_by' => $this->ownerA->id]);

        // Assign once
        $this->actingAs($this->ownerA)->post(
            "/owner/catalog/assign/{$product->slug}",
            ['product_id' => $product->id]
        );

        // Attempt duplicate assignment — Inertia converts ValidationException to a redirect back with session errors
        $response = $this->actingAs($this->ownerA)->post(
            "/owner/catalog/assign/{$product->slug}",
            ['product_id' => $product->id]
        );

        // Inertia redirects back; the error is flashed to the session
        $response->assertRedirect();
        $response->assertSessionHasErrors('product_id');
    }

    public function test_assigned_product_appears_in_owners_shop(): void
    {
        $product = Product::factory()->create(['created_by' => $this->ownerA->id]);

        $this->actingAs($this->ownerA)->post(
            "/owner/catalog/assign/{$product->slug}",
            ['product_id' => $product->id]
        );

        $this->assertTrue(
            $this->shopA->products()->where('products.id', $product->id)->exists()
        );
    }

    public function test_shopless_owner_is_rejected_instead_of_404_when_assigning(): void
    {
        $shoplessOwner = User::factory()->shopOwner()->create();
        $product = Product::factory()->create(['created_by' => $this->ownerA->id]);

        $response = $this->actingAs($shoplessOwner)->post(
            "/owner/catalog/assign/{$product->slug}",
            ['product_id' => $product->id]
        );

        // Friendly redirect, not a 404
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('shop_products', ['product_id' => $product->id]);
    }
}
