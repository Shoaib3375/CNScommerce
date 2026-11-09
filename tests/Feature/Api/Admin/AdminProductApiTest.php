<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_list_products(): void
    {
        Product::factory()->count(3)->create(['created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/products');

        // Laravel paginate returns current_page, data, last_page etc.
        $response->assertOk()
            ->assertJsonStructure(['current_page', 'data', 'last_page', 'total']);
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/products', [
            'name'        => 'API Product',
            'description' => 'A product created via API',
            'base_price'  => 49.99,
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'API Product');

        $this->assertDatabaseHas('products', ['name' => 'API Product']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create(['created_by' => $this->admin->id]);

        // Product model binds by slug
        $response = $this->actingAs($this->admin)->putJson("/api/admin/products/{$product->slug}", [
            'name'        => 'Updated Product',
            'description' => 'Updated description',
            'base_price'  => 59.99,
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'Updated Product');
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create(['created_by' => $this->admin->id]);

        // Product model binds by slug
        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/products/{$product->slug}");

        $response->assertSuccessful();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_search_returns_matching_results(): void
    {
        Product::factory()->create(['name' => 'Wireless Headphones', 'created_by' => $this->admin->id]);
        Product::factory()->create(['name' => 'Mechanical Keyboard', 'created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/products?search=Wireless');

        $response->assertOk();
        $names = array_column($response->json('data'), 'name');
        $this->assertContains('Wireless Headphones', $names);
        $this->assertNotContains('Mechanical Keyboard', $names);
    }

    public function test_create_product_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/products', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'base_price']);
    }
}
