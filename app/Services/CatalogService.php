<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CatalogService
{
    public function browseCatalog(string $search = ''): LengthAwarePaginator
    {
        return Product::with('creator')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->paginate(20);
    }

    public function assignToShop(Shop $shop, Product $product, array $customization): ShopProduct
    {
        return DB::transaction(function () use ($shop, $product, $customization) {
            try {
                /** @var ShopProduct $shopProduct */
                $shopProduct = ShopProduct::create(array_merge([
                    'shop_id'    => $shop->id,
                    'product_id' => $product->id,
                ], $customization));

                return $shopProduct;
            } catch (UniqueConstraintViolationException $e) {
                throw ValidationException::withMessages([
                    'product_id' => ['This product is already assigned to the shop.'],
                ]);
            }
        });
    }

    public function updateShopProduct(ShopProduct $shopProduct, array $data): ShopProduct
    {
        return DB::transaction(function () use ($shopProduct, $data) {
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
                $existing = $shopProduct->thumbnail;

                if ($existing) {
                    Storage::disk('public')->delete($existing);
                }

                $data['thumbnail'] = $data['thumbnail']->store('thumbnails', 'public');
            }

            $shopProduct->update($data);

            return $shopProduct->fresh();
        });
    }

    public function removeFromShop(ShopProduct $shopProduct): void
    {
        DB::transaction(function () use ($shopProduct) {
            if ($shopProduct->thumbnail) {
                Storage::disk('public')->delete($shopProduct->thumbnail);
            }

            $shopProduct->delete();
        });
    }
}
