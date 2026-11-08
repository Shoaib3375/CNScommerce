<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ProductForm from '@/Components/ProductForm.vue';

defineOptions({ layout: OwnerLayout });

const form = useForm({
    name: '',
    description: '',
    base_price: '',
    thumbnail: null as File | null,
});

function submit() {
    form.post('/owner/my-products', {
        forceFormData: true,
    });
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/owner/my-products" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">Add Product</h1>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <ProductForm
                :form="form as any"
                :processing="form.processing"
                @submit="submit"
            />
        </div>
    </div>
</template>
