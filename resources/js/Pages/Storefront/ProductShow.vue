<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import type { Shop, Product, ShopProduct } from '@/types';

defineOptions({ layout: StorefrontLayout });

const props = defineProps<{
    shop: Shop;
    product: Product;
    shopProduct: ShopProduct;
}>();

function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}

// Per spec: shopProduct overrides take priority over global product fields
const displayThumbnail = imgSrc(props.shopProduct.thumbnail ?? props.product.thumbnail);
const displayPrice     = props.shopProduct.price;
const displayDesc      = props.shopProduct.description ?? props.product.description;
</script>

<template>
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
        <!-- Back link -->
        <Link :href="`/shop/${shop.slug}`" class="text-sm text-indigo-600 hover:text-indigo-800">
            ← Back to {{ shop.name }}
        </Link>

        <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <!-- Thumbnail -->
            <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
                <img
                    v-if="displayThumbnail"
                    :src="displayThumbnail"
                    :alt="product.name"
                    class="w-full h-full object-cover"
                />
                <div
                    v-else
                    class="flex flex-col items-center justify-center text-gray-400"
                    aria-hidden="true"
                >
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="mt-2 text-sm">No image available</span>
                </div>
            </div>

            <!-- Product details -->
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ product.name }}</h1>

                <!-- Price: always shopProduct.price, never base_price -->
                <p class="mt-3 text-3xl font-semibold text-indigo-600">${{ displayPrice }}</p>

                <!-- Description: shopProduct.description ?? product.description -->
                <div v-if="displayDesc" class="mt-4 text-gray-600 leading-relaxed whitespace-pre-wrap">
                    {{ displayDesc }}
                </div>
                <p v-else class="mt-4 text-gray-400 italic">No description available.</p>
            </div>
        </div>
    </div>
</template>
