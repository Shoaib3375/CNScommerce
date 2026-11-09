<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ShopProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = ShopProduct::query()
            ->where('is_active', true)
            ->whereHas('shop', fn ($q) => $q->where('status', true))
            ->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%"))
            ->with(['product:id,name,slug,thumbnail,base_price', 'shop:id,name,slug'])
            ->limit(8)
            ->get()
            ->map(fn ($sp) => [
                'id'        => $sp->id,
                'price'     => $sp->price,
                'thumbnail' => $sp->thumbnail ?? $sp->product?->thumbnail,
                'product'   => [
                    'name' => $sp->product?->name,
                    'slug' => $sp->product?->slug,
                ],
                'shop' => [
                    'name' => $sp->shop?->name,
                    'slug' => $sp->shop?->slug,
                ],
            ]);

        return response()->json($results);
    }
}
