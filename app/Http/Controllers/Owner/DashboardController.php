<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $shop = $user->shops()->first();

        $stats = $shop
            ? [
                'assignedProducts' => $shop->products()->count(),
                'activeProducts'   => $shop->products()->wherePivot('is_active', true)->count(),
                'myProducts'       => $user->products()->count(),
            ]
            : [
                'assignedProducts' => 0,
                'activeProducts'   => 0,
                'myProducts'       => $user->products()->count(),
            ];

        return Inertia::render('Owner/Dashboard', [
            'shop'  => $shop,
            'stats' => $stats,
        ]);
    }
}
