<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import axios from '@/axios';
import type { PaginatedData, User } from '@/types';

const owners = ref<PaginatedData<User> | null>(null);
const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleting = ref(false);

const columns = [
    { key: 'name',  label: 'Name' },
    { key: 'email', label: 'Email' },
];

async function fetchOwners() {
    const r = await axios.get('/api/admin/owners');
    owners.value = r.data;
}

onMounted(fetchOwners);

function promptDelete(id: number) { deleteTargetId.value = id; confirmDelete.value = true; }

async function doDelete() {
    if (deleteTargetId.value === null) return;
    deleting.value = true;
    try {
        await axios.delete(`/api/admin/owners/${deleteTargetId.value}`);
        confirmDelete.value = false;
        deleteTargetId.value = null;
        await fetchOwners();
    } finally {
        deleting.value = false;
    }
}
</script>

<template>
    <AdminLayout>
        <div>
            <div class="flex items-center justify-between mb-7">
                <div>
                    <div class="w-8 h-1 bg-indigo-600 rounded mb-2" />
                    <h1 class="text-2xl font-bold text-gray-900">Shop Owners</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage shop owner accounts.</p>
                </div>
                <RouterLink to="/admin/owners/create" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                    <span>+</span> Add Owner
                </RouterLink>
            </div>

            <DataTable v-if="owners" :columns="columns" :rows="(owners.data as unknown) as Record<string, unknown>[]">
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
                        <RouterLink :to="`/admin/owners/${(row as unknown as User).id}/edit`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Edit</RouterLink>
                        <button type="button" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors" @click="promptDelete((row as unknown as User).id)">Delete</button>
                    </div>
                </template>
            </DataTable>

            <ConfirmDialog :show="confirmDelete" message="Delete this owner account? This cannot be undone." @confirm="doDelete" @cancel="confirmDelete = false" />
        </div>
    </AdminLayout>
</template>
