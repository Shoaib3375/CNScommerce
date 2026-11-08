<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Shop;
use App\Models\User;
use App\Services\ShopService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private readonly ShopService $shopService) {}

    public function index(Request $request): Response
    {
        $shops = Shop::with('owner')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Shops/Index', [
            'shops'  => $shops,
            'search' => $request->query('search', ''),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Shops/Create', [
            'owners' => User::where('role', User::ROLE_SHOP_OWNER)
                ->select('id', 'name', 'email')
                ->get(),
        ]);
    }

    public function store(StoreShopRequest $request): RedirectResponse
    {
        $this->authorize('create', Shop::class);

        $this->shopService->create($request->validated(), $request->user());

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop created successfully.');
    }

    public function show(Shop $shop): Response
    {
        return Inertia::render('Admin/Shops/Show', [
            'shop'     => $shop->load('owner'),
            'products' => $shop->products()->with('creator')->paginate(15),
        ]);
    }

    public function edit(Shop $shop): Response
    {
        return Inertia::render('Admin/Shops/Edit', [
            'shop'   => $shop->load('owner'),
            'owners' => User::where('role', User::ROLE_SHOP_OWNER)
                ->select('id', 'name', 'email')
                ->get(),
        ]);
    }

    public function update(UpdateShopRequest $request, Shop $shop): RedirectResponse
    {
        $this->authorize('update', $shop);

        $this->shopService->update($shop, $request->validated());

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $this->authorize('delete', $shop);

        $this->shopService->delete($shop);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully.');
    }
}
