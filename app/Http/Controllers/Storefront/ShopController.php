<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProduct;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(): Response
    {
        $shops = Shop::where('status', true)
            ->withCount(['products as active_products_count' => function ($q) {
                $q->where('shop_products.is_active', true);
            }])
            ->paginate(12);

        // Featured products: latest active shop_products with their global product + shop
        $featured = ShopProduct::where('is_active', true)
            ->with(['product', 'shop'])
            ->whereHas('shop', fn ($q) => $q->where('status', true))
            ->latest()
            ->take(8)
            ->get();

        return Inertia::render('Storefront/Home', [
            'shops'    => $shops,
            'featured' => $featured,
        ]);
    }

    public function show(Shop $shop): Response
    {
        abort_unless($shop->status, 404);

        $products = $shop->products()
            ->wherePivot('is_active', true)
            ->paginate(12);

        return Inertia::render('Storefront/ShopShow', [
            'shop'     => $shop,
            'products' => $products,
        ]);
    }
}
