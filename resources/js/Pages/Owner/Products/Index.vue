<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import Pagination from '@/Components/Pagination.vue';
import type { PaginatedData, ShopProduct } from '@/types';

defineOptions({ layout: OwnerLayout });

defineProps<{
    shopProducts: PaginatedData<ShopProduct>;
}>();

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function promptDelete(id: number) {
    deleteTargetId.value = id;
    confirmDelete.value = true;
}

function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/owner/shop-products/${deleteTargetId.value}`, {
        onFinish: () => {
            confirmDelete.value = false;
            deleteTargetId.value = null;
        },
    });
}
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Assigned Products</h1>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Custom Price</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="shopProducts.data.length === 0">
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                            No products assigned yet. Browse the <Link href="/owner/catalog" class="text-indigo-600 hover:text-indigo-800">catalog</Link>.
                        </td>
                    </tr>
                    <tr v-for="sp in shopProducts.data" :key="sp.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ sp.product?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">${{ sp.price }}</td>
                        <td class="px-4 py-3">
                            <StatusBadge :active="sp.is_active" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link
                                    :href="`/owner/shop-products/${sp.id}/edit`"
                                    class="text-xs text-indigo-600 hover:text-indigo-900 font-medium"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="text-xs text-red-600 hover:text-red-900 font-medium"
                                    @click="promptDelete(sp.id)"
                                >
                                    Unassign
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="shopProducts.links" />

        <ConfirmDialog
            :show="confirmDelete"
            message="Are you sure you want to unassign this product from your shop?"
            @confirm="doDelete"
            @cancel="confirmDelete = false"
        />
    </div>
</template>
