<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import axios from '@/axios';
import type { Shop, Product, ShopProduct } from '@/types';

const route = useRoute();

const shop = ref<Shop | null>(null);
const product = ref<Product | null>(null);
const shopProduct = ref<ShopProduct | null>(null);

onMounted(async () => {
    const r = await axios.get(
        `/api/storefront/shops/${route.params.shopSlug}/products/${route.params.productSlug}`
    );
    shop.value = r.data.shop;
    product.value = r.data.product;
    shopProduct.value = r.data.shopProduct;
});

function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}
</script>

<template>
    <StorefrontLayout>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
            <!-- Back link -->
            <RouterLink v-if="shop" :to="`/shop/${shop.slug}`" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to {{ shop.name }}
            </RouterLink>

            <div v-if="!product || !shopProduct" class="mt-8 text-gray-400 text-sm">Loading…</div>

            <div v-else class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                <!-- Thumbnail -->
                <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
                    <img
                        v-if="imgSrc(shopProduct.thumbnail ?? product.thumbnail)"
                        :src="imgSrc(shopProduct.thumbnail ?? product.thumbnail)!"
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
                    <p class="mt-3 text-3xl font-semibold text-indigo-600">${{ shopProduct.price }}</p>

                    <!-- Description: shopProduct.description ?? product.description -->
                    <div v-if="shopProduct.description ?? product.description" class="mt-4 text-gray-600 leading-relaxed whitespace-pre-wrap">
                        {{ shopProduct.description ?? product.description }}
                    </div>
                    <p v-else class="mt-4 text-gray-400 italic">No description available.</p>
                </div>
            </div>
        </div>
    </StorefrontLayout>
</template>
