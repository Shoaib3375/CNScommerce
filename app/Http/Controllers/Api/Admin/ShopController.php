<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Shop;
use App\Models\User;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private readonly ShopService $shopService) {}

    public function index(Request $request): JsonResponse
    {
        $shops = Shop::with('owner')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return response()->json($shops);
    }

    public function store(StoreShopRequest $request): JsonResponse
    {
        $this->authorize('create', Shop::class);

        $shop = $this->shopService->create($request->validated(), $request->user());

        return response()->json($shop, 201);
    }

    public function show(Shop $shop): JsonResponse
    {
        return response()->json([
            'shop' => $shop->load('owner'),
            'products' => $shop->products()->with('creator')->paginate(15),
        ]);
    }

    public function edit(Shop $shop): JsonResponse
    {
        return response()->json([
            'shop' => $shop->load('owner'),
            'owners' => User::where('role', User::ROLE_SHOP_OWNER)->select('id', 'name', 'email')->get(),
        ]);
    }

    public function update(UpdateShopRequest $request, Shop $shop): JsonResponse
    {
        $this->authorize('update', $shop);

        $this->shopService->update($shop, $request->validated());

        return response()->json($shop);
    }

    public function destroy(Shop $shop): JsonResponse
    {
        $this->authorize('delete', $shop);

        $this->shopService->delete($shop);

        return response()->json(null, 204);
    }
}
