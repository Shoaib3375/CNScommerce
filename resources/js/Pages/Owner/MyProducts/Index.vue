<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import type { PaginatedData, Product } from '@/types';

defineOptions({ layout: OwnerLayout });

defineProps<{
    products: PaginatedData<Product>;
}>();
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
            <Link
                href="/owner/my-products/create"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                + Add Product
            </Link>
        </div>

        <p class="mb-4 text-sm text-gray-500">
            Products you create are added to the shared catalog and may be listed by other shops.
            Only administrators can delete global products — contact an admin if a product must be removed.
        </p>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Thumbnail</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Base Price</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="products.data.length === 0">
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                            You haven't created any products yet.
                        </td>
                    </tr>
                    <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <img
                                v-if="product.thumbnail"
                                :src="`/storage/${product.thumbnail}`"
                                :alt="product.name"
                                class="h-10 w-10 rounded object-cover"
                            />
                            <div v-else class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                N/A
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ product.name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">${{ product.base_price }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link
                                :href="`/owner/my-products/${product.id}/edit`"
                                class="text-xs text-indigo-600 hover:text-indigo-900 font-medium"
                            >
                                Edit
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="products.links" />
    </div>
</template>
