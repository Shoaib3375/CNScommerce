<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import type { PaginatedData, Product } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    products: PaginatedData<Product>;
    search: string;
}>();

const columns = [
    { key: 'thumbnail', label: 'Image' },
    { key: 'name', label: 'Name' },
    { key: 'creator', label: 'Created By' },
    { key: 'base_price', label: 'Base Price' },
];

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function onSearch(value: string) {
    router.get('/admin/products', { search: value }, { preserveState: true, replace: true });
}

function promptDelete(id: number) {
    deleteTargetId.value = id;
    confirmDelete.value = true;
}

function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/admin/products/${deleteTargetId.value}`, {
        onFinish: () => {
            confirmDelete.value = false;
            deleteTargetId.value = null;
        },
    });
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <Link
                href="/admin/products/create"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                + Add Product
            </Link>
        </div>

        <DataTable
            :columns="columns"
            :rows="(products.data as unknown) as Record<string, unknown>[]"
            :searchable="true"
            @search="onSearch"
        >
            <template #cell-thumbnail="{ row }">
                <img
                    v-if="(row as unknown as Product).thumbnail"
                    :src="`/storage/${(row as unknown as Product).thumbnail}`"
                    :alt="(row as unknown as Product).name"
                    class="h-10 w-10 rounded object-cover"
                />
                <div v-else class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                    N/A
                </div>
            </template>
            <template #cell-creator="{ row }">
                {{ (row as unknown as Product).creator?.name ?? '—' }}
            </template>
            <template #cell-base_price="{ row }">
                ${{ (row as unknown as Product).base_price }}
            </template>
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-2">
                    <Link
                        :href="`/admin/products/${(row as unknown as Product).id}/edit`"
                        class="text-xs text-indigo-600 hover:text-indigo-900 font-medium"
                    >
                        Edit
                    </Link>
                    <button
                        type="button"
                        class="text-xs text-red-600 hover:text-red-900 font-medium"
                        @click="promptDelete((row as unknown as Product).id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog
            :show="confirmDelete"
            message="Are you sure you want to delete this product?"
            @confirm="doDelete"
            @cancel="confirmDelete = false"
        />
    </div>
</template>
