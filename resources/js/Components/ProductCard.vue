<script setup lang="ts">
import type { Product, ShopProduct } from '@/types';

const props = defineProps<{
    product: Product | ShopProduct;
    showShopPrice?: boolean;
}>();

/** Handles both external URLs (https://...) and local storage paths */
function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}

function isShopProduct(p: Product | ShopProduct): p is ShopProduct {
    return 'shop_id' in p;
}

function getPrice(): string {
    if (props.showShopPrice && isShopProduct(props.product)) {
        return props.product.price;
    }
    if (!isShopProduct(props.product)) {
        return props.product.base_price;
    }
    return props.product.product?.base_price ?? '';
}

function getName(): string {
    if (isShopProduct(props.product)) {
        return props.product.product?.name ?? '';
    }
    return props.product.name;
}

function getThumbnail(): string | null {
    if (isShopProduct(props.product)) {
        return props.product.thumbnail ?? props.product.product?.thumbnail ?? null;
    }
    return props.product.thumbnail;
}
</script>

<template>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <!-- Thumbnail -->
        <div class="aspect-square bg-gray-100 overflow-hidden">
            <img
                v-if="imgSrc(getThumbnail())"
                :src="imgSrc(getThumbnail())!"
                :alt="getName()"
                class="w-full h-full object-cover"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-gray-300"
                aria-hidden="true"
            >
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <!-- Info -->
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ getName() }}</h3>
            <p class="mt-1 text-sm font-bold text-indigo-600">${{ getPrice() }}</p>
            <slot />
        </div>
    </div>
</template>
