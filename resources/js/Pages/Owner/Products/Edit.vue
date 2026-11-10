<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import ImageUpload from '@/Components/ImageUpload.vue';
import axios from '@/axios';
import type { ShopProduct } from '@/types';

const route = useRoute();
const router = useRouter();

const shopProduct = reactive<Partial<ShopProduct>>({});

const form = reactive({
    price: '',
    description: '',
    thumbnail: null as string | File | null,
    is_active: true,
    processing: false,
    errors: {} as Record<string, string[]>,
});

onMounted(async () => {
    const r = await axios.get(`/api/owner/shop-products/${route.params.id}/edit`);
    const sp: ShopProduct = r.data.shopProduct ?? r.data;
    Object.assign(shopProduct, sp);
    form.price = sp.price;
    form.description = sp.description ?? '';
    form.thumbnail = sp.thumbnail ?? null;
    form.is_active = sp.is_active;
});

async function submit() {
    form.processing = true;
    form.errors = {};
    try {
        const fd = new FormData();
        fd.append('_method', 'PATCH');
        fd.append('price', form.price);
        fd.append('description', form.description);
        fd.append('is_active', form.is_active ? '1' : '0');
        if (form.thumbnail instanceof File) fd.append('thumbnail', form.thumbnail);
        await axios.post(`/api/owner/shop-products/${route.params.id}`, fd);
        router.push('/owner/shop-products');
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
    <OwnerLayout>
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-7">
                <RouterLink to="/owner/shop-products" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-blue-100 flex items-center justify-center text-gray-500 hover:text-blue-600 transition-colors text-sm">←</RouterLink>
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
                    <p class="text-sm font-bold text-blue-800">{{ (shopProduct as ShopProduct).product?.name ?? '—' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Price</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">$</span>
                        <input v-model="form.price" type="number" min="0" step="0.01" required class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                    </div>
                    <p v-if="form.errors.price" class="mt-1.5 text-xs text-red-500">{{ form.errors.price[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Description <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea v-model="form.description" rows="4" placeholder="Override the global description for your shop…" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 focus:bg-white resize-none" />
                    <p v-if="form.errors.description" class="mt-1.5 text-xs text-red-500">{{ form.errors.description[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Thumbnail <span class="text-gray-400 font-normal">(optional)</span></label>
                    <ImageUpload
                        :existing-url="typeof form.thumbnail === 'string' ? form.thumbnail : null"
                        class="mt-1"
                        @update:model-value="(file: File | null) => { form.thumbnail = file; }"
                    />
                    <p v-if="form.errors.thumbnail" class="mt-1.5 text-xs text-red-500">{{ form.errors.thumbnail[0] }}</p>
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
                    <RouterLink to="/owner/shop-products" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</RouterLink>
                    <button type="button" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm disabled:opacity-50 transition-colors" @click="submit">
                        {{ form.processing ? 'Saving…' : 'Save Changes' }}
                    </button>
                </div>
            </div>
        </div>
    </OwnerLayout>
</template>
