<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import type { Shop, ShopProduct } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    shop: Shop;
    products: ShopProduct[];
}>();
</script>

<template>
    <div>
        <div class="flex items-center gap-3 mb-6">
            <Link href="/admin/shops" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">{{ shop.name }}</h1>
            <StatusBadge :active="shop.status" />
        </div>

        <!-- Shop details -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Slug</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ shop.slug }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Owner</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ shop.owner?.name ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-gray-500 uppercase">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ shop.description ?? '—' }}</dd>
                </div>
            </dl>
            <div class="mt-4">
                <Link :href="`/admin/shops/${shop.id}/edit`" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Edit Shop
                </Link>
            </div>
        </div>

        <!-- Assigned products -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Assigned Products</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Custom Price</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="products.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">No products assigned.</td>
                    </tr>
                    <tr v-for="sp in products" :key="sp.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ sp.product?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">${{ sp.price }}</td>
                        <td class="px-4 py-3">
                            <StatusBadge :active="sp.is_active" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
