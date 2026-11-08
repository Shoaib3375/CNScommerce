<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $admin    = User::where('email', 'admin@example.com')->firstOrFail();
            $owner1   = User::where('email', 'owner1@example.com')->firstOrFail();
            $owner2   = User::where('email', 'owner2@example.com')->firstOrFail();
            $shopAlpha = Shop::where('slug', 'shop-alpha')->firstOrFail();
            $shopBeta  = Shop::where('slug', 'shop-beta')->firstOrFail();

            // Products 1–5 created by admin
            $productData = [
                ['name' => 'Wireless Headphones',   'base_price' => 89.99,  'created_by' => $admin->id],
                ['name' => 'Mechanical Keyboard',    'base_price' => 129.99, 'created_by' => $admin->id],
                ['name' => 'USB-C Hub',              'base_price' => 49.99,  'created_by' => $admin->id],
                ['name' => 'Laptop Stand',           'base_price' => 39.99,  'created_by' => $admin->id],
                ['name' => 'Webcam HD',              'base_price' => 75.00,  'created_by' => $admin->id],
                // Products 6–8 created by owner1
                ['name' => 'Desk Lamp LED',          'base_price' => 34.99,  'created_by' => $owner1->id],
                ['name' => 'Mouse Pad XL',           'base_price' => 19.99,  'created_by' => $owner1->id],
                ['name' => 'Cable Management Kit',   'base_price' => 14.99,  'created_by' => $owner1->id],
                // Products 9–10 created by owner2
                ['name' => 'Monitor Light Bar',      'base_price' => 59.99,  'created_by' => $owner2->id],
                ['name' => 'Ergonomic Wrist Rest',   'base_price' => 24.99,  'created_by' => $owner2->id],
            ];

            $products = [];
            foreach ($productData as $data) {
                $products[] = Product::create(array_merge($data, [
                    'slug'        => Str::slug($data['name']),
                    'description' => 'A high-quality '.$data['name'].' for everyday use.',
                    'thumbnail'   => null,
                ]));
            }

            // Assign products 1–6 (index 0–5) to Shop Alpha
            foreach (array_slice($products, 0, 6) as $i => $product) {
                $shopAlpha->products()->attach($product->id, [
                    'price'       => round($product->base_price * 1.1, 2), // 10% markup
                    'description' => 'Available at Shop Alpha: '.$product->name,
                    'thumbnail'   => null,
                    'is_active'   => true,
                ]);
            }

            // Assign products 4–9 (index 3–8) to Shop Beta
            // Products at index 3, 4, 5 overlap with Alpha — DIFFERENT prices prove per-shop isolation
            foreach (array_slice($products, 3, 6) as $i => $product) {
                $shopBeta->products()->attach($product->id, [
                    'price'       => round($product->base_price * 1.2, 2), // 20% markup (different from Alpha)
                    'description' => 'Available at Shop Beta: '.$product->name,
                    'thumbnail'   => null,
                    'is_active'   => true,
                ]);
            }
        });
    }
}
