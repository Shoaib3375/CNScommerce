<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Owner;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Storefront;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ---------------------------------------------------------------------------
// Storefront — public routes
// ---------------------------------------------------------------------------

Route::get('/', [Storefront\ShopController::class, 'index'])
    ->name('storefront.home');

Route::get('/shop/{shop:slug}', [Storefront\ShopController::class, 'show'])
    ->name('storefront.shop.show');

Route::get('/shop/{shop:slug}/products/{product:slug}', [Storefront\ProductController::class, 'show'])
    ->name('storefront.product.show');

// Live product search (JSON)
Route::get('/api/search', Storefront\SearchController::class)
    ->name('storefront.search');

// ---------------------------------------------------------------------------
// Generic dashboard redirect — used by Breeze auth controllers after login
// ---------------------------------------------------------------------------

Route::get('/dashboard', function (Request $request) {
    /** @var \App\Models\User|null $user */
    $user = $request->user();

    if ($user && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user && $user->isShopOwner()) {
        return redirect()->route('owner.dashboard');
    }

    return redirect()->route('storefront.home');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------------------------------------------------------
// Auth routes (Breeze)
// ---------------------------------------------------------------------------

require __DIR__.'/auth.php';

// ---------------------------------------------------------------------------
// Profile routes (Breeze)
// ---------------------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------------------------------------------------------------------
// Admin routes
// ---------------------------------------------------------------------------

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('shops', Admin\ShopController::class);
        Route::resource('owners', Admin\OwnerController::class)->except(['show']);
        Route::resource('products', Admin\ProductController::class)->except(['show']);
    });

// ---------------------------------------------------------------------------
// Owner routes
// ---------------------------------------------------------------------------

Route::prefix('owner')
    ->middleware(['auth', 'verified', 'role:shop_owner'])
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [Owner\DashboardController::class, 'index'])
            ->name('dashboard');

        // Shared catalog — browse and assign products
        Route::get('/catalog', [Owner\CatalogController::class, 'index'])
            ->name('catalog.index');
        Route::post('/catalog/assign/{product:slug}', [Owner\CatalogController::class, 'assign'])
            ->name('catalog.assign');

        // Shop product customizations (assigned products)
        Route::resource('shop-products', Owner\ShopProductController::class)
            ->only(['index', 'edit', 'update', 'destroy']);

        // Owner's own products (created by owner)
        Route::resource('my-products', Owner\ProductController::class)
            ->except(['show']);
    });
