<?php

namespace Tests\Feature\Isolation;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopIsolationTest extends TestCase
{
    use RefreshDatabase;

    private User $ownerA;
    private User $ownerB;
    private Shop $shopAlpha;
    private Shop $shopBeta;
    private Product $sharedProduct;
    private ShopProduct $shopProductAlpha;
    private ShopProduct $shopProductBeta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ownerA = User::factory()->shopOwner()->create();
        $this->ownerB = User::factory()->shopOwner()->create();

        $this->shopAlpha = Shop::factory()->create(['owner_id' => $this->ownerA->id]);
        $this->shopBeta  = Shop::factory()->create(['owner_id' => $this->ownerB->id]);

        $admin = User::factory()->admin()->create();
        $this->sharedProduct = Product::factory()->create(['created_by' => $admin->id]);

        // Assign the same product to both shops with DIFFERENT prices
        $this->shopAlpha->products()->attach($this->sharedProduct->id, [
            'price'       => 100.00,
            'description' => 'Alpha pricing',
            'thumbnail'   => null,
            'is_active'   => true,
        ]);

        $this->shopBeta->products()->attach($this->sharedProduct->id, [
            'price'       => 150.00,
            'description' => 'Beta pricing',
            'thumbnail'   => null,
            'is_active'   => true,
        ]);

        $this->shopProductAlpha = ShopProduct::where('shop_id', $this->shopAlpha->id)
            ->where('product_id', $this->sharedProduct->id)
            ->firstOrFail();

        $this->shopProductBeta = ShopProduct::where('shop_id', $this->shopBeta->id)
            ->where('product_id', $this->sharedProduct->id)
            ->firstOrFail();
    }

    public function test_owner_a_cannot_patch_shop_product_belonging_to_owner_b(): void
    {
        $response = $this->actingAs($this->ownerA)->patch(
            "/owner/shop-products/{$this->shopProductBeta->id}",
            ['price' => 999.00, 'is_active' => true]
        );

        $response->assertStatus(403);
    }

    public function test_same_product_has_independent_prices_in_each_shop(): void
    {
        // Both shop_product records exist independently
        $this->assertDatabaseHas('shop_products', [
            'shop_id'    => $this->shopAlpha->id,
            'product_id' => $this->sharedProduct->id,
            'price'      => 100.00,
        ]);

        $this->assertDatabaseHas('shop_products', [
            'shop_id'    => $this->shopBeta->id,
            'product_id' => $this->sharedProduct->id,
            'price'      => 150.00,
        ]);

        // Prices must differ
        $this->assertNotEquals(
            $this->shopProductAlpha->price,
            $this->shopProductBeta->price
        );
    }

    public function test_owner_a_cannot_delete_shop_product_from_shop_beta(): void
    {
        $response = $this->actingAs($this->ownerA)->delete(
            "/owner/shop-products/{$this->shopProductBeta->id}"
        );

        $response->assertStatus(403);

        // Record must still exist
        $this->assertDatabaseHas('shop_products', ['id' => $this->shopProductBeta->id]);
    }
}
