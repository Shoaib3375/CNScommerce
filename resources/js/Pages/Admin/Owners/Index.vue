<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import type { PaginatedData, User } from '@/types';

defineOptions({ layout: AdminLayout });
defineProps<{ owners: PaginatedData<User> }>();

const columns = [
    { key: 'name',  label: 'Name' },
    { key: 'email', label: 'Email' },
];

const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleteForm = useForm({});

function promptDelete(id: number) { deleteTargetId.value = id; confirmDelete.value = true; }
function doDelete() {
    if (deleteTargetId.value === null) return;
    deleteForm.delete(`/admin/owners/${deleteTargetId.value}`, {
        onFinish: () => { confirmDelete.value = false; deleteTargetId.value = null; },
    });
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-7">
            <div>
                <div class="w-8 h-1 bg-indigo-600 rounded mb-2" />
                <h1 class="text-2xl font-bold text-gray-900">Shop Owners</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage shop owner accounts.</p>
            </div>
            <Link href="/admin/owners/create" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                <span>+</span> Add Owner
            </Link>
        </div>

        <DataTable :columns="columns" :rows="(owners.data as unknown) as Record<string, unknown>[]">
            <template #cell-name="{ row }">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">
                        {{ (row as unknown as User).name?.charAt(0) }}
                    </div>
                    <span class="font-medium">{{ (row as unknown as User).name }}</span>
                </div>
            </template>
            <template #cell-email="{ row }">
                <span class="text-gray-500">{{ (row as unknown as User).email }}</span>
            </template>
            <template #actions="{ row }">
                <div class="flex items-center justify-end gap-3">
                    <Link :href="`/admin/owners/${(row as unknown as User).id}/edit`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Edit</Link>
                    <button type="button" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors" @click="promptDelete((row as unknown as User).id)">Delete</button>
                </div>
            </template>
        </DataTable>

        <ConfirmDialog :show="confirmDelete" message="Delete this owner account? This cannot be undone." @confirm="doDelete" @cancel="confirmDelete = false" />
    </div>
</template>
