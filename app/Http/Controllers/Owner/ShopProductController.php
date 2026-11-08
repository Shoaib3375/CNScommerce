<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateShopProductRequest;
use App\Models\ShopProduct;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ShopProductController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    private function authorizeShopProduct(ShopProduct $shopProduct): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($shopProduct->shop->owner_id === $user->id, 403);
    }

    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $shopProducts = ShopProduct::whereHas('shop', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->with('product')
            ->paginate(15);

        return Inertia::render('Owner/Products/Index', [
            'shopProducts' => $shopProducts,
        ]);
    }

    public function edit(ShopProduct $shopProduct): Response
    {
        $this->authorizeShopProduct($shopProduct);

        return Inertia::render('Owner/Products/Edit', [
            'shopProduct' => $shopProduct->load('product'),
        ]);
    }

    public function update(UpdateShopProductRequest $request, ShopProduct $shopProduct): RedirectResponse
    {
        $this->authorizeShopProduct($shopProduct);

        $this->catalogService->updateShopProduct($shopProduct, $request->validated());

        return redirect()->route('owner.shop-products.index')
            ->with('success', 'Shop product updated successfully.');
    }

    public function destroy(ShopProduct $shopProduct): RedirectResponse
    {
        $this->authorizeShopProduct($shopProduct);

        $this->catalogService->removeFromShop($shopProduct);

        return redirect()->route('owner.shop-products.index')
            ->with('success', 'Product removed from shop successfully.');
    }
}
