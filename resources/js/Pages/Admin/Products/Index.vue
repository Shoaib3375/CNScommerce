<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import type { PaginatedData, Product } from '@/types';

defineOptions({ layout: AdminLayout });
defineProps<{ products: PaginatedData<Product>; search: string }>();

const columns = [
    { key: 'thumbnail', label: 'Image' },
    { key: 'name',      label: 'Name' },
    { key: 'creator',   label: 'Created By' },
    { key: 'base_price',label: 'Base Price' },
];

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function imgSrc(path: string | null): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}
function onSearch(value: string) {
    router.get('/admin/products', { search: value }, { preserveState: true, replace: true });
}
function promptDelete(id: number) { deleteTargetId.value = id; confirmDelete.value = true; }
function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/admin/products/${deleteTargetId.value}`, {
        onFinish: () => { confirmDelete.value = false; deleteTargetId.value = null; },
    });
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-indigo-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-sm text-gray-500 mt-0.5">Global product catalog.</p>
            </div>
            <Link href="/admin/products/create" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                <span>+</span> Add Product
            </Link>
        </div>

        <DataTable :columns="columns" :rows="(products.data as unknown) as Record<string, unknown>[]" :searchable="true" @search="onSearch">
            <template #cell-thumbnail="{ row }">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                    <img v-if="imgSrc((row as unknown as Product).thumbnail)" :src="imgSrc((row as unknown as Product).thumbnail)!" :alt="(row as unknown as Product).name" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-xs">—</div>
                </div>
            </template>
            <template #cell-creator="{ row }">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 text-xs font-bold shrink-0">
                        {{ (row as unknown as Product).creator?.name?.charAt(0) ?? '?' }}
                    </div>
                    <span class="text-gray-600">{{ (row as unknown as Product).creator?.name ?? '—' }}</span>
                </div>
            </template>
            <template #cell-base_price="{ row }">
                <span class="font-semibold text-indigo-600">${{ (row as unknown as Product).base_price }}</span>
            </template>
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-3">
                    <Link :href="`/admin/products/${(row as unknown as Product).id}/edit`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Edit</Link>
                    <button type="button" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors" @click="promptDelete((row as unknown as Product).id)">Delete</button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog :show="confirmDelete" message="Delete this product from the catalog? This cannot be undone." @confirm="doDelete" @cancel="confirmDelete = false" />
    </div>
</template>
