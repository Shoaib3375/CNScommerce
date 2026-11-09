<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import type { PaginatedData, Product } from '@/types';

defineOptions({ layout: OwnerLayout });
defineProps<{ products: PaginatedData<Product> }>();

function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-blue-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
                <p class="text-sm text-gray-500 mt-0.5">Products you've added to the global catalog.</p>
            </div>
            <Link href="/owner/my-products/create" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                + Add Product
            </Link>
        </div>

        <div class="mb-4 flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 text-sm text-blue-700">
            <span class="text-lg mt-0.5">ℹ️</span>
            <span>Products you create are added to the shared catalog. Only administrators can delete global products.</span>
        </div>

        <!-- Empty state -->
        <div v-if="products.data.length === 0" class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
            <div class="text-5xl mb-3">📦</div>
            <p class="text-sm font-medium text-gray-500">You haven't created any products yet.</p>
            <Link href="/owner/my-products/create" class="mt-3 inline-block text-sm font-semibold text-blue-600 hover:text-blue-800">+ Add your first product →</Link>
        </div>

        <!-- Product grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div
                v-for="product in products.data"
                :key="product.id"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
            >
                <div class="aspect-square overflow-hidden bg-gray-50">
                    <img v-if="imgSrc(product.thumbnail)" :src="imgSrc(product.thumbnail)!" :alt="product.name" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">📷</div>
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ product.name }}</h3>
                    <p class="mt-1 text-sm font-bold text-blue-600">${{ product.base_price }}</p>
                    <div class="mt-3">
                        <Link :href="`/owner/my-products/${product.id}/edit`" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                            ✏️ Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <Pagination :links="products.links" class="mt-6" />
    </div>
</template>
