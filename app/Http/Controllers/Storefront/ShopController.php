<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(): Response
    {
        $shops = Shop::where('status', true)->paginate(12);

        return Inertia::render('Storefront/Home', [
            'shops' => $shops,
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
