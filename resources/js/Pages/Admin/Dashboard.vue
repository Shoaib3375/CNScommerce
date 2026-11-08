<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

defineProps<{
    stats: {
        shops: number;
        owners: number;
        products: number;
        activeAssignments: number;
    };
}>();

const cards = [
    { key: 'shops' as const, label: 'Total Shops', icon: '🏪', color: 'bg-blue-50 text-blue-700' },
    { key: 'owners' as const, label: 'Shop Owners', icon: '👤', color: 'bg-purple-50 text-purple-700' },
    { key: 'products' as const, label: 'Products', icon: '📦', color: 'bg-green-50 text-green-700' },
    { key: 'activeAssignments' as const, label: 'Active Assignments', icon: '✅', color: 'bg-yellow-50 text-yellow-700' },
];
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                v-for="card in cards"
                :key="card.key"
                class="bg-white rounded-lg border border-gray-200 p-5 flex items-center gap-4"
            >
                <div :class="['text-3xl p-3 rounded-full', card.color]">
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
