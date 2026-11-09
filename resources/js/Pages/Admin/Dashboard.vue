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
    { key: 'shops'            as const, label: 'Total Shops',       icon: '🏪', from: 'from-indigo-500', to: 'to-indigo-600' },
    { key: 'owners'           as const, label: 'Shop Owners',        icon: '👤', from: 'from-blue-500',   to: 'to-blue-600' },
    { key: 'products'         as const, label: 'Products',           icon: '📦', from: 'from-violet-500', to: 'to-violet-600' },
    { key: 'activeAssignments'as const, label: 'Active Assignments', icon: '✅', from: 'from-emerald-500',to: 'to-emerald-600' },
];
</script>

<template>
    <div>
        <!-- Page header -->
        <div class="mb-8">
            <div class="w-10 h-1 bg-indigo-600 rounded mb-3" />
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back! Here's an overview of the platform.</p>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div
                v-for="card in cards"
                :key="card.key"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow"
            >
                <div :class="['w-12 h-12 rounded-2xl bg-gradient-to-br flex items-center justify-center text-2xl shadow-sm', card.from, card.to]">
                    {{ card.icon }}
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ stats[card.key] }}</p>
                    <p class="text-sm text-gray-500">{{ card.label }}</p>
                </div>
            </div>
        </div>

        <!-- Quick links -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="/admin/shops/create" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-indigo-300 hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-lg group-hover:bg-indigo-200 transition-colors">🏪</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">New Shop</p>
                        <p class="text-xs text-gray-400">Create a shop</p>
                    </div>
                </div>
            </a>
            <a href="/admin/owners/create" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-blue-300 hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 text-lg group-hover:bg-blue-200 transition-colors">👤</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">New Owner</p>
                        <p class="text-xs text-gray-400">Add shop owner</p>
                    </div>
                </div>
            </a>
            <a href="/admin/products/create" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-violet-300 hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-lg group-hover:bg-violet-200 transition-colors">📦</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">New Product</p>
                        <p class="text-xs text-gray-400">Add to catalog</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>
