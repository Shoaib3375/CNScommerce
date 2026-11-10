<script setup lang="ts">
import { reactive, ref, watch, onMounted } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from '@/axios';
import type { User } from '@/types';

const router = useRouter();
const owners = ref<User[]>([]);

onMounted(async () => {
    const r = await axios.get('/api/admin/shops/create');
    owners.value = r.data.owners ?? [];
});

const form = reactive({
    name: '',
    slug: '',
    description: '',
    status: true,
    owner_id: '' as string | number,
    processing: false,
    errors: {} as Record<string, string[]>,
});

watch(() => form.name, (v: string) => {
    form.slug = v.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
});

async function submit() {
    form.processing = true;
    form.errors = {};
    try {
        await axios.post('/api/admin/shops', {
            name: form.name,
            slug: form.slug,
            description: form.description,
            status: form.status,
            owner_id: form.owner_id,
        });
        router.push('/admin/shops');
    } catch (e: unknown) {
        const err = e as { response?: { status?: number; data?: { errors?: Record<string, string[]> } } };
        if (err.response?.status === 422) {
            form.errors = err.response.data?.errors ?? {};
        }
    } finally {
        form.processing = false;
    }
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-2xl">
            <!-- Header -->
            <div class="flex items-center gap-3 mb-7">
                <RouterLink to="/admin/shops" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 transition-colors text-sm">←</RouterLink>
                <div>
                    <div class="w-6 h-1 bg-indigo-600 rounded mb-1" />
                    <h1 class="text-xl font-bold text-gray-900">Create Shop</h1>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Shop Name</label>
                    <input v-model="form.name" type="text" required placeholder="e.g. Tech Store" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                    <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slug</label>
                    <input v-model="form.slug" type="text" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white font-mono" />
                    <p v-if="form.errors.slug" class="mt-1.5 text-xs text-red-500">{{ form.errors.slug[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea v-model="form.description" rows="3" placeholder="Brief description of the shop…" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white resize-none" />
                    <p v-if="form.errors.description" class="mt-1.5 text-xs text-red-500">{{ form.errors.description[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Owner</label>
                    <select v-model="form.owner_id" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white">
                        <option value="" disabled>Select an owner…</option>
                        <option v-for="owner in owners" :key="owner.id" :value="owner.id">{{ owner.name }} ({{ owner.email }})</option>
                    </select>
                    <p v-if="form.errors.owner_id" class="mt-1.5 text-xs text-red-500">{{ form.errors.owner_id[0] }}</p>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Active</p>
                        <p class="text-xs text-gray-400 mt-0.5">Shop is visible to customers</p>
                    </div>
                    <button type="button" role="switch" :aria-checked="form.status" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition-colors', form.status ? 'bg-indigo-600' : 'bg-gray-300']" @click="form.status = !form.status">
                        <span :class="['inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform', form.status ? 'translate-x-6' : 'translate-x-1']" />
                    </button>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <RouterLink to="/admin/shops" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</RouterLink>
                    <button type="button" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm disabled:opacity-50 transition-colors" @click="submit">
                        {{ form.processing ? 'Creating…' : 'Create Shop' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
