<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateShopProductRequest;
use App\Models\ShopProduct;
use App\Models\User;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class ShopProductController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    private function authorizeShopProduct(ShopProduct $shopProduct): void
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($shopProduct->shop->owner_id === $user->id, 403);
    }

    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $shopProducts = ShopProduct::whereHas('shop', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->with('product')
            ->paginate(15);

        return response()->json(['shopProducts' => $shopProducts]);
    }

    public function edit(ShopProduct $shopProduct): JsonResponse
    {
        $this->authorizeShopProduct($shopProduct);

        return response()->json(['shopProduct' => $shopProduct->load('product')]);
    }

    public function update(UpdateShopProductRequest $request, ShopProduct $shopProduct): JsonResponse
    {
        $this->authorizeShopProduct($shopProduct);

        $this->catalogService->updateShopProduct($shopProduct, $request->validated());

        return response()->json($shopProduct);
    }

    public function destroy(ShopProduct $shopProduct): JsonResponse
    {
        $this->authorizeShopProduct($shopProduct);

        $this->catalogService->removeFromShop($shopProduct);

        return response()->json(null, 204);
    }
}
