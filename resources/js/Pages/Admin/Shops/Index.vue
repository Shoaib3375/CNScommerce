<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import type { PaginatedData, Shop } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    shops: PaginatedData<Shop>;
    search: string;
}>();

const columns = [
    { key: 'name',   label: 'Shop Name' },
    { key: 'owner',  label: 'Owner' },
    { key: 'status', label: 'Status' },
];

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function onSearch(value: string) {
    router.get('/admin/shops', { search: value }, { preserveState: true, replace: true });
}
function promptDelete(id: number) {
    deleteTargetId.value = id;
    confirmDelete.value = true;
}
function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/admin/shops/${deleteTargetId.value}`, {
        onFinish: () => { confirmDelete.value = false; deleteTargetId.value = null; },
    });
}
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-indigo-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">Shops</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage all shops on the platform.</p>
            </div>
            <Link
                href="/admin/shops/create"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors"
            >
                <span class="text-base">+</span> Create Shop
            </Link>
        </div>

        <DataTable
            :columns="columns"
            :rows="(shops.data as unknown) as Record<string, unknown>[]"
            :searchable="true"
            @search="onSearch"
        >
            <template #cell-owner="{ row }">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold">
                        {{ (row as unknown as Shop).owner?.name?.charAt(0) ?? '?' }}
                    </div>
                    <span>{{ (row as unknown as Shop).owner?.name ?? '—' }}</span>
                </div>
            </template>
            <template #cell-status="{ row }">
                <StatusBadge :active="(row as unknown as Shop).status" />
            </template>
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-3">
                    <Link
                        :href="`/admin/shops/${(row as unknown as Shop).id}/edit`"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                    >Edit</Link>
                    <button
                        type="button"
                        class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors"
                        @click="promptDelete((row as unknown as Shop).id)"
                    >Delete</button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog
            :show="confirmDelete"
            message="Are you sure you want to delete this shop? This cannot be undone."
            @confirm="doDelete"
            @cancel="confirmDelete = false"
        />
    </div>
</template>
