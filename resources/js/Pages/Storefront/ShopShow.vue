<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';
import type { PaginatedData, Shop, ShopProduct } from '@/types';

defineOptions({ layout: StorefrontLayout });

defineProps<{
    shop: Shop;
    products: PaginatedData<ShopProduct>;
}>();
</script>

<template>
    <div>
        <!-- Shop header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <Link href="/" class="text-sm text-indigo-600 hover:text-indigo-800">← All Shops</Link>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ shop.name }}</h1>
            <p v-if="shop.description" class="mt-2 text-gray-500">{{ shop.description }}</p>
        </div>

        <!-- Products -->
        <div v-if="products.data.length === 0" class="text-center py-12 text-gray-500">
            This shop has no products yet.
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <Link
                v-for="sp in products.data"
                :key="sp.id"
                :href="`/shop/${shop.slug}/products/${sp.product?.slug}`"
                class="block no-underline"
            >
                <ProductCard :product="sp" :show-shop-price="true" />
            </Link>
        </div>

        <Pagination :links="products.links" />
    </div>
</template>
