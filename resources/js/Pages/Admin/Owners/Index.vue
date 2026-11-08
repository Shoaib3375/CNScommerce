<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import type { PaginatedData, User } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    owners: PaginatedData<User>;
}>();

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
];

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function promptDelete(id: number) {
    deleteTargetId.value = id;
    confirmDelete.value = true;
}

function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/admin/owners/${deleteTargetId.value}`, {
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
            <h1 class="text-2xl font-bold text-gray-900">Shop Owners</h1>
            <Link
                href="/admin/owners/create"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                + Add Owner
            </Link>
        </div>

        <DataTable
            :columns="columns"
            :rows="(owners.data as unknown) as Record<string, unknown>[]"
        >
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-2">
                    <Link
                        :href="`/admin/owners/${(row as unknown as User).id}/edit`"
                        class="text-xs text-indigo-600 hover:text-indigo-900 font-medium"
                    >
                        Edit
                    </Link>
                    <button
                        type="button"
                        class="text-xs text-red-600 hover:text-red-900 font-medium"
                        @click="promptDelete((row as unknown as User).id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog
            :show="confirmDelete"
            message="Are you sure you want to delete this owner? This action cannot be undone."
            @confirm="doDelete"
            @cancel="confirmDelete = false"
        />
    </div>
</template>
