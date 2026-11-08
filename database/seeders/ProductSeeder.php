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
            $admin   = User::where('email', 'admin@example.com')->firstOrFail();
            $owner1  = User::where('email', 'owner1@example.com')->firstOrFail();
            $owner2  = User::where('email', 'owner2@example.com')->firstOrFail();
            $shopAlpha = Shop::where('slug', 'shop-alpha')->firstOrFail();
            $shopBeta  = Shop::where('slug', 'shop-beta')->firstOrFail();

            // Real product images via picsum.photos (stable, no API key needed)
            // Format: https://picsum.photos/id/{id}/600/600
            $productData = [
                // Admin-created (1–5)
                [
                    'name'       => 'Wireless Headphones',
                    'base_price' => 89.99,
                    'created_by' => $admin->id,
                    'thumbnail'  => 'https://picsum.photos/id/3/600/600',
                    'description' => 'Premium wireless headphones with active noise cancellation and 30-hour battery life.',
                ],
                [
                    'name'       => 'Mechanical Keyboard',
                    'base_price' => 129.99,
                    'created_by' => $admin->id,
                    'thumbnail'  => 'https://picsum.photos/id/48/600/600',
                    'description' => 'Full-size mechanical keyboard with tactile switches, RGB backlight, and aluminium frame.',
                ],
                [
                    'name'       => 'USB-C Hub',
                    'base_price' => 49.99,
                    'created_by' => $admin->id,
                    'thumbnail'  => 'https://picsum.photos/id/180/600/600',
                    'description' => '7-in-1 USB-C hub with HDMI 4K, 100W PD charging, SD card reader, and 3 USB-A ports.',
                ],
                [
                    'name'       => 'Laptop Stand',
                    'base_price' => 39.99,
                    'created_by' => $admin->id,
                    'thumbnail'  => 'https://picsum.photos/id/119/600/600',
                    'description' => 'Adjustable aluminium laptop stand with 6 height levels, foldable and portable.',
                ],
                [
                    'name'       => 'Webcam HD',
                    'base_price' => 75.00,
                    'created_by' => $admin->id,
                    'thumbnail'  => 'https://picsum.photos/id/250/600/600',
                    'description' => '1080p webcam with built-in stereo microphone, auto-focus, and plug-and-play USB.',
                ],
                // Owner1-created (6–8)
                [
                    'name'       => 'Desk Lamp LED',
                    'base_price' => 34.99,
                    'created_by' => $owner1->id,
                    'thumbnail'  => 'https://picsum.photos/id/399/600/600',
                    'description' => 'Smart LED desk lamp with touch control, 5 brightness levels, and USB charging port.',
                ],
                [
                    'name'       => 'Mouse Pad XL',
                    'base_price' => 19.99,
                    'created_by' => $owner1->id,
                    'thumbnail'  => 'https://picsum.photos/id/201/600/600',
                    'description' => 'Extra-large gaming mouse pad (900×400mm) with non-slip rubber base and stitched edges.',
                ],
                [
                    'name'       => 'Cable Management Kit',
                    'base_price' => 14.99,
                    'created_by' => $owner1->id,
                    'thumbnail'  => 'https://picsum.photos/id/366/600/600',
                    'description' => '50-piece cable management kit with clips, velcro ties, and adhesive cable channels.',
                ],
                // Owner2-created (9–10)
                [
                    'name'       => 'Monitor Light Bar',
                    'base_price' => 59.99,
                    'created_by' => $owner2->id,
                    'thumbnail'  => 'https://picsum.photos/id/442/600/600',
                    'description' => 'E-reading monitor light bar with asymmetric optical design — no screen glare.',
                ],
                [
                    'name'       => 'Ergonomic Wrist Rest',
                    'base_price' => 24.99,
                    'created_by' => $owner2->id,
                    'thumbnail'  => 'https://picsum.photos/id/463/600/600',
                    'description' => 'Memory foam wrist rest with non-slip base, supports natural wrist position during typing.',
                ],
            ];

            $products = [];
            foreach ($productData as $data) {
                $products[] = Product::create([
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']),
                    'description' => $data['description'],
                    'base_price'  => $data['base_price'],
                    'thumbnail'   => $data['thumbnail'],
                    'created_by'  => $data['created_by'],
                ]);
            }

            // Shop Alpha: products 1–6 (indices 0–5) with 10% markup
            foreach (array_slice($products, 0, 6) as $product) {
                $shopAlpha->products()->attach($product->id, [
                    'price'       => round($product->base_price * 1.10, 2),
                    'description' => null, // inherit global description
                    'thumbnail'   => null, // inherit global thumbnail
                    'is_active'   => true,
                ]);
            }

            // Shop Beta: products 4–9 (indices 3–8) with 20% markup
            // Products 4, 5, 6 overlap with Alpha → different prices prove isolation
            foreach (array_slice($products, 3, 6) as $product) {
                $shopBeta->products()->attach($product->id, [
                    'price'       => round($product->base_price * 1.20, 2),
                    'description' => null, // inherit global description
                    'thumbnail'   => null, // inherit global thumbnail
                    'is_active'   => true,
                ]);
            }
        });
    }
}
