<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Shop $shop, Product $product): Response
    {
        abort_unless($shop->status, 404);

        $shopProduct = ShopProduct::where('shop_id', $shop->id)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Storefront/ProductShow', [
            'shop'        => $shop,
            'product'     => $product,
            'shopProduct' => $shopProduct,
        ]);
    }
}
