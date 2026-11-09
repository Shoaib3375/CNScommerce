<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import Pagination from '@/Components/Pagination.vue';
import axios from '@/axios';
import type { PaginatedData, ShopProduct } from '@/types';

const shopProducts = ref<PaginatedData<ShopProduct> | null>(null);
const confirmDelete = ref(false);
const deleteTargetId = ref<number | null>(null);
const deleting = ref(false);

async function fetchProducts() {
    const r = await axios.get('/api/owner/shop-products');
    shopProducts.value = r.data;
}

onMounted(fetchProducts);

function promptDelete(id: number) { deleteTargetId.value = id; confirmDelete.value = true; }

async function doDelete() {
    if (deleteTargetId.value === null) return;
    deleting.value = true;
    try {
        await axios.delete(`/api/owner/shop-products/${deleteTargetId.value}`);
        confirmDelete.value = false;
        deleteTargetId.value = null;
        await fetchProducts();
    } finally {
        deleting.value = false;
    }
}

function imgSrc(path: string | null | undefined): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}

function thumbnail(sp: ShopProduct): string | null {
    return imgSrc(sp.thumbnail ?? sp.product?.thumbnail ?? null);
}
</script>

<template>
    <OwnerLayout>
        <div>
            <!-- Header -->
            <div class="flex items-center justify-between mb-7">
                <div>
                    <div class="w-8 h-1 bg-blue-600 rounded mb-2" />
                    <h1 class="text-2xl font-bold text-gray-900">Assigned Products</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Customize pricing and details for your shop.</p>
                </div>
                <RouterLink
                    to="/owner/catalog"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors"
                >
                    🗂️ Browse Catalog
                </RouterLink>
            </div>

            <div v-if="!shopProducts" class="text-gray-400 text-sm">Loading…</div>

            <template v-else>
                <!-- Empty state -->
                <div v-if="shopProducts.data.length === 0" class="bg-white rounded-2xl border border-gray-100 shadow-sm py-20 text-center">
                    <div class="text-5xl mb-3">📋</div>
                    <p class="text-sm font-medium text-gray-500 mb-2">No products assigned yet.</p>
                    <RouterLink to="/owner/catalog" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                        Browse the catalog →
                    </RouterLink>
                </div>

                <!-- Card grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    <div
                        v-for="sp in shopProducts.data"
                        :key="sp.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col"
                    >
                        <!-- Product image -->
                        <div class="aspect-square overflow-hidden bg-gray-50 relative">
                            <img
                                v-if="thumbnail(sp)"
                                :src="thumbnail(sp)!"
                                :alt="sp.product?.name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-200">
                                <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="absolute top-2 right-2">
                                <StatusBadge :active="sp.is_active" />
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ sp.product?.name ?? '—' }}</h3>
                            <p class="mt-1 text-base font-bold text-blue-600">${{ sp.price }}</p>
                            <p v-if="sp.product?.base_price && sp.price !== sp.product.base_price" class="text-xs text-gray-400 line-through">
                                Base: ${{ sp.product.base_price }}
                            </p>
                            <div class="mt-auto pt-3 flex gap-2">
                                <RouterLink
                                    :to="`/owner/shop-products/${sp.id}/edit`"
                                    class="flex-1 text-center text-xs font-semibold py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors"
                                >
                                    ✏️ Edit
                                </RouterLink>
                                <button
                                    type="button"
                                    class="flex-1 text-xs font-semibold py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                    @click="promptDelete(sp.id)"
                                >
                                    🗑️ Unassign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <Pagination :links="shopProducts.links" class="mt-6" />
            </template>

            <ConfirmDialog
                :show="confirmDelete"
                message="Unassign this product from your shop? It will no longer appear in your storefront."
                @confirm="doDelete"
                @cancel="confirmDelete = false"
            />
        </div>
    </OwnerLayout>
</template>
