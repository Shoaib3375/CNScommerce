<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ProductForm from '@/Components/ProductForm.vue';
import axios from '@/axios';
import type { Product } from '@/types';

const route = useRoute();
const router = useRouter();

const form = reactive({
    name: '',
    description: '',
    base_price: '',
    thumbnail: null as string | File | null,
    processing: false,
    errors: {} as Record<string, string>,
});

onMounted(async () => {
    const r = await axios.get(`/api/owner/my-products/${route.params.id}/edit`);
    const product: Product = r.data.product ?? r.data;
    form.name = product.name;
    form.description = product.description ?? '';
    form.base_price = product.base_price;
    form.thumbnail = product.thumbnail ?? null;
});

async function submit() {
    form.processing = true;
    form.errors = {};
    try {
        const fd = new FormData();
        fd.append('_method', 'PATCH');
        fd.append('name', form.name);
        fd.append('description', form.description);
        fd.append('base_price', String(form.base_price));
        if (form.thumbnail instanceof File) fd.append('thumbnail', form.thumbnail);
        await axios.post(`/api/owner/my-products/${route.params.id}`, fd);
        router.push('/owner/my-products');
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
    <OwnerLayout>
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-7">
                <RouterLink to="/owner/my-products" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-blue-100 flex items-center justify-center text-gray-500 hover:text-blue-600 transition-colors text-sm">←</RouterLink>
                <div>
                    <div class="w-6 h-1 bg-blue-600 rounded mb-1" />
                    <h1 class="text-xl font-bold text-gray-900">Edit Product</h1>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <ProductForm :form="form as any" :processing="form.processing" @submit="submit" />
            </div>
        </div>
    </OwnerLayout>
</template>
