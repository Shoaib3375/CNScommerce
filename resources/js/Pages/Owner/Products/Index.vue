<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import Pagination from '@/Components/Pagination.vue';
import type { PaginatedData, ShopProduct } from '@/types';

defineOptions({ layout: OwnerLayout });
defineProps<{ shopProducts: PaginatedData<ShopProduct> }>();

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function promptDelete(id: number) { deleteTargetId.value = id; confirmDelete.value = true; }
function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/owner/shop-products/${deleteTargetId.value}`, {
        onFinish: () => { confirmDelete.value = false; deleteTargetId.value = null; },
    });
}
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-blue-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">Assigned Products</h1>
                <p class="text-sm text-gray-500 mt-0.5">Customize pricing and details for your shop.</p>
            </div>
            <Link href="/owner/catalog" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                🗂️ Browse Catalog
            </Link>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-50 to-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Custom Price</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="shopProducts.data.length === 0">
                            <td colspan="4" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <span class="text-4xl">📋</span>
                                    <p class="text-sm font-medium">No products assigned yet.</p>
                                    <Link href="/owner/catalog" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Browse the catalog →</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="sp in shopProducts.data" :key="sp.id" class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-5 py-3.5 text-sm font-medium text-gray-900">
                                {{ sp.product?.name ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-bold text-blue-600">${{ sp.price }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <StatusBadge :active="sp.is_active" />
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <Link :href="`/owner/shop-products/${sp.id}/edit`" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Edit</Link>
                                    <button type="button" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors" @click="promptDelete(sp.id)">Unassign</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :links="shopProducts.links" class="mt-4" />

        <ConfirmDialog :show="confirmDelete" message="Unassign this product from your shop?" @confirm="doDelete" @cancel="confirmDelete = false" />
    </div>
</template>
