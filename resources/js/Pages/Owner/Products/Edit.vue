<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ImageUpload from '@/Components/ImageUpload.vue';
import type { ShopProduct } from '@/types';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    shopProduct: ShopProduct;
}>();

const form = useForm({
    _method: 'PATCH',
    price: props.shopProduct.price,
    description: props.shopProduct.description ?? '',
    thumbnail: props.shopProduct.thumbnail as string | File | null,
    is_active: props.shopProduct.is_active,
});

function submit() {
    form.post(`/owner/products/${props.shopProduct.id}`, {
        forceFormData: true,
    });
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/owner/products" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">Edit Assigned Product</h1>
        </div>

        <p class="text-sm text-gray-500 mb-4">
            Product: <span class="font-medium text-gray-900">{{ shopProduct.product?.name }}</span>
        </p>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Custom Price</label>
                    <input
                        id="price"
                        v-model="form.price"
                        type="number"
                        min="0"
                        step="0.01"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.price" class="mt-1 text-xs text-red-600">{{ form.errors.price }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Custom Description <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Custom Thumbnail <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <ImageUpload
                        :existing-url="typeof form.thumbnail === 'string' ? form.thumbnail : null"
                        class="mt-1"
                        @update:model-value="(file) => { form.thumbnail = file; }"
                    />
                    <p v-if="form.errors.thumbnail" class="mt-1 text-xs text-red-600">{{ form.errors.thumbnail }}</p>
                </div>

                <!-- Active toggle -->
                <div class="flex items-center gap-3">
                    <label class="block text-sm font-medium text-gray-700">Active</label>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="form.is_active"
                        :class="[
                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                            form.is_active ? 'bg-indigo-600' : 'bg-gray-300'
                        ]"
                        @click="form.is_active = !form.is_active"
                    >
                        <span
                            :class="[
                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                form.is_active ? 'translate-x-6' : 'translate-x-1'
                            ]"
                        />
                    </button>
                    <p v-if="form.errors.is_active" class="text-xs text-red-600">{{ form.errors.is_active }}</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <Link href="/owner/products" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
