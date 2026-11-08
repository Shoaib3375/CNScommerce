<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_create_a_product(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name'        => 'Brand New Widget',
            'description' => 'A fantastic widget',
            'base_price'  => 99.99,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'Brand New Widget']);
    }

    public function test_created_product_appears_in_the_shared_catalog(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', [
            'name'        => 'Catalog Widget',
            'description' => 'In the catalog',
            'base_price'  => 49.99,
        ]);

        $product = Product::where('name', 'Catalog Widget')->first();
        $this->assertNotNull($product);
        $this->assertTrue(Product::all()->contains($product));
    }

    public function test_admin_can_update_a_product(): void
    {
        $product = Product::factory()->create(['created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->patch("/admin/products/{$product->slug}", [
            'name'       => 'Updated Product Name',
            'base_price' => 199.99,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'Updated Product Name', 'id' => $product->id]);
    }
}
