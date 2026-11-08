<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import type { PaginatedData, Product, Shop } from '@/types';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    products: PaginatedData<Product>;
    shop: Shop | null;
    assignedProductIds: number[];
    search: string;
}>();

function onSearch(event: Event) {
    router.get('/owner/catalog', { search: (event.target as HTMLInputElement).value }, { preserveState: true, replace: true });
}

function isAssigned(productId: number): boolean {
    return props.assignedProductIds.includes(productId);
}

function assign(productId: number) {
    const form = useForm({});
    form.post(`/owner/catalog/${productId}/assign`);
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Product Catalog</h1>
            <div class="relative">
                <input
                    type="text"
                    :value="search"
                    placeholder="Search products..."
                    class="w-64 pl-3 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @input="onSearch"
                />
            </div>
        </div>

        <div v-if="products.data.length === 0" class="text-center py-12 text-gray-500">
            No products found in the catalog.
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <ProductCard
                v-for="product in products.data"
                :key="product.id"
                :product="product"
                :show-shop-price="false"
            >
                <div class="mt-3">
                    <button
                        v-if="isAssigned(product.id)"
                        type="button"
                        disabled
                        class="w-full text-xs py-1.5 px-3 rounded-md bg-green-100 text-green-700 font-medium cursor-default"
                    >
                        ✓ Assigned
                    </button>
                    <button
                        v-else
                        type="button"
                        class="w-full text-xs py-1.5 px-3 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors"
                        @click="assign(product.id)"
                    >
                        Assign to My Shop
                    </button>
                </div>
            </ProductCard>
        </div>
    </div>
</template>
