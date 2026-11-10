<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import axios from '@/axios';
import type { PaginatedData, Shop, Product } from '@/types';

const route = useRoute();

// The backend sends Product models with pivot data attached.
interface ProductWithPivot extends Product {
    pivot: {
        id: number;
        price: string;
        description: string | null;
        thumbnail: string | null;
        is_active: boolean;
    };
}

const shop = ref<Shop | null>(null);
const products = ref<PaginatedData<ProductWithPivot> | null>(null);

onMounted(async () => {
    const r = await axios.get(`/api/storefront/shops/${route.params.slug}`);
    shop.value = r.data.shop;
    products.value = r.data.products;
});

function thumbnail(p: ProductWithPivot): string | null {
    return p.pivot.thumbnail ?? p.thumbnail;
}

function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}

function price(p: ProductWithPivot): string {
    return p.pivot.price;
}
</script>

<template>
    <StorefrontLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <!-- Shop header -->
            <div class="mb-8">
                <RouterLink to="/" class="text-sm text-indigo-600 hover:text-indigo-800">← All Shops</RouterLink>
                <h1 v-if="shop" class="mt-2 text-3xl font-bold text-gray-900">{{ shop.name }}</h1>
                <p v-if="shop?.description" class="mt-2 text-gray-500">{{ shop.description }}</p>
            </div>

            <div v-if="!products">
                <p class="text-gray-400 text-sm">Loading…</p>
            </div>

            <template v-else>
                <!-- Empty state -->
                <div v-if="products.data.length === 0" class="text-center py-16 text-gray-400">
                    <svg class="mx-auto w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    <p class="text-sm">This shop has no products yet.</p>
                </div>

                <!-- Product grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <RouterLink
                        v-for="p in products.data"
                        :key="p.id"
                        :to="`/shop/${shop?.slug}/products/${p.slug}`"
                        class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <!-- Image -->
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img
                                v-if="thumbnail(p)"
                                :src="imgSrc(thumbnail(p))!"
                                :alt="p.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-gray-300"
                            >
                                <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ p.name }}</h3>
                            <p class="mt-1 text-base font-bold text-indigo-600">${{ price(p) }}</p>
                            <p v-if="p.description" class="mt-1 text-xs text-gray-400 line-clamp-2">{{ p.description }}</p>
                        </div>
                    </RouterLink>
                </div>

                <Pagination :links="products.links" class="mt-8" />
            </template>
        </div>
    </StorefrontLayout>
</template>
