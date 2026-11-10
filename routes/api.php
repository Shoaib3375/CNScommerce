<?php

use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Owner;
use App\Http\Controllers\Api\Storefront;
use App\Http\Controllers\Storefront\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Public / Auth routes
// ---------------------------------------------------------------------------

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');

Route::get('/user', function (Request $request) {
    if (! $request->user()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    return response()->json($request->user());
})->middleware('auth:sanctum')->name('api.user');

// ---------------------------------------------------------------------------
// Storefront — public
// ---------------------------------------------------------------------------

Route::prefix('storefront')->name('api.storefront.')->group(function () {
    Route::get('/home', [Storefront\ShopController::class, 'index'])->name('home');
    Route::get('/shops/{shop:slug}', [Storefront\ShopController::class, 'show'])->name('shops.show');
    Route::get('/shops/{shop:slug}/products/{product:slug}', [Storefront\ProductController::class, 'show'])->name('shops.products.show');
});

// Live search
Route::get('/search', SearchController::class)->name('api.search');

// ---------------------------------------------------------------------------
// Admin routes
// ---------------------------------------------------------------------------

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->name('api.admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('shops', Admin\ShopController::class);
        Route::resource('owners', Admin\OwnerController::class)->except(['show']);
        Route::resource('products', Admin\ProductController::class)->except(['show']);
    });

// ---------------------------------------------------------------------------
// Owner routes
// ---------------------------------------------------------------------------

Route::prefix('owner')
    ->middleware(['auth:sanctum', 'role:shop_owner'])
    ->name('api.owner.')
    ->group(function () {
        Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/catalog', [Owner\CatalogController::class, 'index'])->name('catalog.index');
        Route::post('/catalog/assign/{product:slug}', [Owner\CatalogController::class, 'assign'])->name('catalog.assign');

        Route::resource('shop-products', Owner\ShopProductController::class)
            ->only(['index', 'edit', 'update', 'destroy']);

        Route::resource('my-products', Owner\ProductController::class)
            ->except(['show']);
    });
