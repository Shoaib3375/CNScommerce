<?php

namespace App\Http\Controllers\Api\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    public function index(): JsonResponse
    {
        $shops = Shop::where('status', true)
            ->withCount(['products as active_products_count' => fn ($q) => $q->where('shop_products.is_active', true)])
            ->paginate(12);

        $featured = ShopProduct::where('is_active', true)
            ->with(['product', 'shop'])
            ->whereHas('shop', fn ($q) => $q->where('status', true))
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'shops' => $shops,
            'featured' => $featured,
        ]);
    }

    public function show(Shop $shop): JsonResponse
    {
        abort_unless($shop->status, 404);

        $products = $shop->products()
            ->wherePivot('is_active', true)
            ->paginate(12);

        return response()->json([
            'shop' => $shop,
            'products' => $products,
        ]);
    }
}
