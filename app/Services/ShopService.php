<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopService
{
    public function create(array $data, User $creator): Shop
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['slug'] = $this->uniqueSlug(Str::slug($data['name']));

            return Shop::create($data);
        });
    }

    public function update(Shop $shop, array $data): Shop
    {
        return DB::transaction(function () use ($shop, $data) {
            if (isset($data['name'])) {
                $base = Str::slug($data['name']);
                if ($base !== $shop->slug) {
                    $data['slug'] = $this->uniqueSlug($base, $shop->id);
                }
            }

            $shop->update($data);

            return $shop->fresh();
        });
    }

    public function delete(Shop $shop): void
    {
        DB::transaction(function () use ($shop) {
            $shop->delete();
        });
    }

    public function assignOwner(Shop $shop, int $userId): Shop
    {
        return DB::transaction(function () use ($shop, $userId) {
            $shop->update(['owner_id' => $userId]);

            return $shop->fresh();
        });
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            Shop::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
