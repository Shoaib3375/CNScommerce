<script setup lang="ts">
import { reactive } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ProductForm from '@/Components/ProductForm.vue';
import axios from '@/axios';

const router = useRouter();

const form = reactive({
    name: '',
    description: '',
    base_price: '',
    thumbnail: null as File | null,
    processing: false,
    errors: {} as Record<string, string>,
});

async function submit() {
    form.processing = true;
    form.errors = {};
    try {
        const fd = new FormData();
        fd.append('name', form.name);
        fd.append('description', form.description);
        fd.append('base_price', form.base_price);
        if (form.thumbnail) fd.append('thumbnail', form.thumbnail);
        await axios.post('/api/admin/products', fd);
        router.push('/admin/products');
    } catch (e: unknown) {
        const err = e as { response?: { status?: number; data?: { errors?: Record<string, string[]> } } };
        if (err.response?.status === 422) {
            const raw = err.response.data?.errors ?? {};
            form.errors = Object.fromEntries(Object.entries(raw).map(([k, v]) => [k, v[0]]));
        }
    } finally {
        form.processing = false;
    }
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-7">
                <RouterLink to="/admin/products" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 transition-colors text-sm">←</RouterLink>
                <div>
                    <div class="w-6 h-1 bg-indigo-600 rounded mb-1" />
                    <h1 class="text-xl font-bold text-gray-900">Add Product</h1>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <ProductForm :form="form as any" :processing="form.processing" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
