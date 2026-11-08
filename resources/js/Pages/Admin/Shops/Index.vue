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
    { key: 'name', label: 'Shop Name' },
    { key: 'owner', label: 'Owner' },
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
            <h1 class="text-2xl font-bold text-gray-900">Shops</h1>
            <Link
                href="/admin/shops/create"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                + Create Shop
            </Link>
        </div>

        <DataTable
            :columns="columns"
            :rows="(shops.data as unknown) as Record<string, unknown>[]"
            :searchable="true"
            @search="onSearch"
        >
            <template #cell-owner="{ row }">
                {{ (row as unknown as Shop).owner?.name ?? '—' }}
            </template>
            <template #cell-status="{ row }">
                <StatusBadge :active="(row as unknown as Shop).status" />
            </template>
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-2">
                    <Link
                        :href="`/admin/shops/${(row as unknown as Shop).id}`"
                        class="text-xs text-gray-600 hover:text-gray-900 font-medium"
                    >
                        View
                    </Link>
                    <Link
                        :href="`/admin/shops/${(row as unknown as Shop).id}/edit`"
                        class="text-xs text-indigo-600 hover:text-indigo-900 font-medium"
                    >
                        Edit
                    </Link>
                    <button
                        type="button"
                        class="text-xs text-red-600 hover:text-red-900 font-medium"
                        @click="promptDelete((row as unknown as Shop).id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog
            :show="confirmDelete"
            message="Are you sure you want to delete this shop? This action cannot be undone."
            @confirm="doDelete"
            @cancel="confirmDelete = false"
        />
    </div>
</template>
