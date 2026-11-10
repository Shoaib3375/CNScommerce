<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'shops' => Shop::count(),
                'owners' => User::where('role', User::ROLE_SHOP_OWNER)->count(),
                'products' => Product::count(),
                'activeAssignments' => ShopProduct::where('is_active', true)->count(),
            ],
        ]);
    }
}
