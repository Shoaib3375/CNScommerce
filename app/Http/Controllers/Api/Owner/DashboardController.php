<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        $stats = $shop
            ? [
                'assignedProducts' => $shop->products()->count(),
                'activeProducts' => $shop->products()->wherePivot('is_active', true)->count(),
                'myProducts' => $user->products()->count(),
            ]
            : [
                'assignedProducts' => 0,
                'activeProducts' => 0,
                'myProducts' => $user->products()->count(),
            ];

        return response()->json([
            'shop' => $shop,
            'stats' => $stats,
        ]);
    }
}
