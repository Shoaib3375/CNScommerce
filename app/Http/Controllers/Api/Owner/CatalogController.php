<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        $search = $request->query('search', '');

        $products = $this->catalogService->browseCatalog($search);

        $assignedProductIds = $shop
            ? $shop->products()->pluck('products.id')->toArray()
            : [];

        return response()->json([
            'products' => $products,
            'shop' => $shop,
            'assignedProductIds' => $assignedProductIds,
            'search' => $search,
        ]);
    }

    public function assign(Request $request, Product $product): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        if (! $shop) {
            return response()->json(['error' => 'You do not have a shop'], 422);
        }

        $shopProduct = $this->catalogService->assignToShop($shop, $product, [
            'price' => $product->base_price,
            'description' => null,
            'thumbnail' => null,
            'is_active' => true,
        ]);

        return response()->json($shopProduct, 201);
    }
}
