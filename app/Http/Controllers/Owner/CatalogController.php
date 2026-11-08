<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\AssignProductRequest;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        $products = $this->catalogService->browseCatalog($request->query('search', ''));

        // Collect IDs of products already assigned to this owner's shop
        $assignedProductIds = $shop
            ? $shop->products()->pluck('products.id')->toArray()
            : [];

        return Inertia::render('Owner/Catalog/Index', [
            'products'           => $products,
            'shop'               => $shop,
            'assignedProductIds' => $assignedProductIds,
            'search'             => $request->query('search', ''),
        ]);
    }

    public function assign(AssignProductRequest $request, Product $product): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        if (! $shop) {
            return redirect()->back()->with('error', 'You do not have a shop to assign products to.');
        }

        $this->catalogService->assignToShop($shop, $product, [
            'price'       => $product->base_price,
            'description' => null,
            'thumbnail'   => null,
            'is_active'   => true,
        ]);

        return redirect()->back()->with('success', 'Product assigned to your shop successfully.');
    }
}
