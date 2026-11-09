<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ProductForm from '@/Components/ProductForm.vue';
import type { Product } from '@/types';

defineOptions({ layout: OwnerLayout });
const props = defineProps<{ product: Product }>();

const form = useForm({
    _method: 'PATCH',
    name: props.product.name,
    description: props.product.description ?? '',
    base_price: props.product.base_price,
    thumbnail: props.product.thumbnail as string | File | null,
});
function submit() { form.post(`/owner/my-products/${props.product.id}`, { forceFormData: true }); }
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-7">
            <Link href="/owner/my-products" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-blue-100 flex items-center justify-center text-gray-500 hover:text-blue-600 transition-colors text-sm">←</Link>
            <div>
                <div class="w-6 h-1 bg-blue-600 rounded mb-1" />
                <h1 class="text-xl font-bold text-gray-900">Edit Product</h1>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <ProductForm :form="form as any" :processing="form.processing" @submit="submit" />
        </div>
    </div>
</template>
