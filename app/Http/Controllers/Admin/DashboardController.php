<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'shops'             => Shop::count(),
                'owners'            => User::where('role', User::ROLE_SHOP_OWNER)->count(),
                'products'          => Product::count(),
                'activeAssignments' => ShopProduct::where('is_active', true)->count(),
            ],
        ]);
    }
}
