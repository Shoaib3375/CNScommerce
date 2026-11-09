<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ImageUpload from '@/Components/ImageUpload.vue';
import type { ShopProduct } from '@/types';

defineOptions({ layout: OwnerLayout });
const props = defineProps<{ shopProduct: ShopProduct }>();

const form = useForm({
    _method: 'PATCH',
    price: props.shopProduct.price,
    description: props.shopProduct.description ?? '',
    thumbnail: props.shopProduct.thumbnail as string | File | null,
    is_active: props.shopProduct.is_active,
});

function submit() {
    form.post(`/owner/shop-products/${props.shopProduct.id}`, { forceFormData: true });
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-7">
            <Link href="/owner/shop-products" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-blue-100 flex items-center justify-center text-gray-500 hover:text-blue-600 transition-colors text-sm">←</Link>
            <div>
                <div class="w-6 h-1 bg-blue-600 rounded mb-1" />
                <h1 class="text-xl font-bold text-gray-900">Customize Listing</h1>
            </div>
        </div>

        <!-- Product name badge -->
        <div class="mb-4 flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3">
            <span class="text-2xl">📦</span>
            <div>
                <p class="text-xs text-blue-500 font-medium">Product</p>
                <p class="text-sm font-bold text-blue-800">{{ shopProduct.product?.name ?? '—' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Price</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">$</span>
                    <input v-model="form.price" type="number" min="0" step="0.01" required class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                </div>
                <p v-if="form.errors.price" class="mt-1.5 text-xs text-red-500">{{ form.errors.price }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Description <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea v-model="form.description" rows="4" placeholder="Override the global description for your shop…" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 focus:bg-white resize-none" />
                <p v-if="form.errors.description" class="mt-1.5 text-xs text-red-500">{{ form.errors.description }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Thumbnail <span class="text-gray-400 font-normal">(optional)</span></label>
                <ImageUpload :existing-url="typeof form.thumbnail === 'string' ? form.thumbnail : null" class="mt-1" @update:model-value="(file) => { form.thumbnail = file; }" />
                <p v-if="form.errors.thumbnail" class="mt-1.5 text-xs text-red-500">{{ form.errors.thumbnail }}</p>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Active Listing</p>
                    <p class="text-xs text-gray-400 mt-0.5">Visible to customers in your shop</p>
                </div>
                <button type="button" role="switch" :aria-checked="form.is_active" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition-colors', form.is_active ? 'bg-blue-600' : 'bg-gray-300']" @click="form.is_active = !form.is_active">
                    <span :class="['inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform', form.is_active ? 'translate-x-6' : 'translate-x-1']" />
                </button>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <Link href="/owner/shop-products" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</Link>
                <button type="button" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm disabled:opacity-50 transition-colors" @click="submit">
                    {{ form.processing ? 'Saving…' : 'Save Changes' }}
                </button>
            </div>
        </div>
    </div>
</template>
