<script setup lang="ts">
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import type { Shop } from '@/types';

defineOptions({ layout: OwnerLayout });

defineProps<{
    shop: Shop | null;
    stats: {
        assignedProducts: number;
        activeProducts: number;
        myProducts: number;
    };
}>();

const cards = [
    { key: 'assignedProducts' as const, label: 'Assigned Products', icon: '📋' },
    { key: 'activeProducts' as const, label: 'Active Products', icon: '✅' },
    { key: 'myProducts' as const, label: 'My Products', icon: '📦' },
];
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p v-if="shop" class="text-sm text-gray-500 mb-6">Managing: <span class="font-medium text-gray-700">{{ shop.name }}</span></p>
        <p v-else class="text-sm text-yellow-600 mb-6">No shop assigned yet. Contact your administrator.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div
                v-for="card in cards"
                :key="card.key"
                class="bg-white rounded-lg border border-gray-200 p-5 flex items-center gap-4"
            >
                <div class="text-3xl p-3 rounded-full bg-indigo-50 text-indigo-700">
                    {{ card.icon }}
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ stats[card.key] }}</p>
                    <p class="text-sm text-gray-500">{{ card.label }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
