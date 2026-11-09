<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import type { PaginatedData, Product, Shop } from '@/types';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    products: PaginatedData<Product>;
    shop: Shop | null;
    assignedProductIds: number[];
    search: string;
}>();

const assigning = ref<string | null>(null);

function onSearch(event: Event) {
    router.get('/owner/catalog', { search: (event.target as HTMLInputElement).value }, { preserveState: true, replace: true });
}
function isAssigned(id: number): boolean {
    return props.assignedProductIds.includes(id);
}
function assign(slug: string) {
    if (assigning.value) return;
    assigning.value = slug;
    router.post(`/owner/catalog/assign/${slug}`, {}, {
        preserveScroll: true,
        onFinish: () => { assigning.value = null; },
    });
}

function imgSrc(path: string | null | undefined): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-start justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-blue-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">Product Catalog</h1>
                <p class="text-sm text-gray-500 mt-0.5">Browse all available products and add them to your shop.</p>
            </div>
            <!-- Search -->
            <div class="relative hidden sm:block">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                </svg>
                <input
                    type="text"
                    :value="search"
                    placeholder="Search products…"
                    class="pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-56 transition"
                    @input="onSearch"
                />
            </div>
        </div>

        <!-- No-shop banner -->
        <div v-if="!shop" class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 text-sm text-amber-700">
            <span class="text-xl">⚠️</span>
            <span>You don't have a shop yet. Contact an administrator to get one assigned.</span>
        </div>

        <!-- Mobile search -->
        <div class="relative sm:hidden mb-5">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
            <input type="text" :value="search" placeholder="Search products…" class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" @input="onSearch" />
        </div>

        <!-- Empty state -->
        <div v-if="products.data.length === 0" class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-3">📦</div>
            <p class="text-sm font-medium">No products found in the catalog.</p>
        </div>

        <!-- Product grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div
                v-for="product in products.data"
                :key="product.id"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
            >
                <!-- Image -->
                <div class="aspect-square overflow-hidden bg-gray-50">
                    <img
                        v-if="imgSrc(product.thumbnail)"
                        :src="imgSrc(product.thumbnail)!"
                        :alt="product.name"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">📷</div>
                </div>

                <!-- Info -->
                <div class="p-3">
                    <h3 class="text-xs font-semibold text-gray-900 truncate">{{ product.name }}</h3>
                    <p class="mt-0.5 text-sm font-bold text-blue-600">${{ product.base_price }}</p>

                    <!-- Action button -->
                    <div class="mt-2.5">
                        <span
                            v-if="isAssigned(product.id)"
                            class="flex items-center justify-center gap-1.5 w-full text-xs py-1.5 px-3 rounded-xl bg-emerald-100 text-emerald-700 font-semibold"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" /> Assigned
                        </span>
                        <button
                            v-else-if="shop"
                            type="button"
                            :disabled="assigning === product.slug"
                            class="w-full text-xs py-1.5 px-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors disabled:opacity-60 disabled:cursor-wait"
                            @click="assign(product.slug)"
                        >
                            {{ assigning === product.slug ? 'Assigning…' : '+ Assign to Shop' }}
                        </button>
                        <span v-else class="flex items-center justify-center w-full text-xs py-1.5 px-3 rounded-xl bg-gray-100 text-gray-400 font-medium cursor-not-allowed">
                            No Shop
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
