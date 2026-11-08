<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data, User $creator): Product
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;
            $data['slug'] = $this->uniqueSlug(Str::slug($data['name']));

            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
                $data['thumbnail'] = $this->handleThumbnailUpload($data['thumbnail']);
            }

            return Product::create($data);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            if (isset($data['name'])) {
                $base = Str::slug($data['name']);
                if ($base !== $product->slug) {
                    $data['slug'] = $this->uniqueSlug($base, $product->id);
                }
            }

            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
                $data['thumbnail'] = $this->handleThumbnailUpload($data['thumbnail'], $product->thumbnail);
            }

            $product->update($data);

            return $product->fresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $product->delete();
        });
    }

    public function handleThumbnailUpload(?UploadedFile $file, ?string $existing = null): ?string
    {
        if ($file === null) {
            return null;
        }

        if ($existing) {
            Storage::disk('public')->delete($existing);
        }

        return $file->store('thumbnails', 'public');
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
