<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
    { key: 'assignedProducts' as const, label: 'Assigned Products', icon: '📋', from: 'from-blue-500',    to: 'to-blue-600' },
    { key: 'activeProducts'   as const, label: 'Active Listings',   icon: '✅', from: 'from-emerald-500', to: 'to-emerald-600' },
    { key: 'myProducts'       as const, label: 'My Products',       icon: '📦', from: 'from-violet-500',  to: 'to-violet-600' },
];

const quickLinks = [
    { label: 'Browse Catalog',    href: '/owner/catalog',       icon: '🗂️', color: 'hover:border-blue-300',   bg: 'bg-blue-100',   text: 'text-blue-600' },
    { label: 'Assigned Products', href: '/owner/shop-products', icon: '📋', color: 'hover:border-emerald-300', bg: 'bg-emerald-100', text: 'text-emerald-600' },
    { label: 'My Products',       href: '/owner/my-products',   icon: '📦', color: 'hover:border-violet-300',  bg: 'bg-violet-100',  text: 'text-violet-600' },
];
</script>

<template>
    <div>
        <!-- Page header -->
        <div class="mb-8">
            <div class="w-10 h-1 bg-blue-600 rounded mb-3" />
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p v-if="shop" class="text-sm text-gray-500 mt-1">
                Managing: <span class="font-semibold text-blue-600">{{ shop.name }}</span>
            </p>
            <div v-else class="mt-2 flex items-center gap-2 text-sm text-amber-600 bg-amber-50 border border-amber-200 px-4 py-2.5 rounded-xl w-fit">
                ⚠️ No shop assigned yet — contact your administrator.
            </div>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
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
            <Link
                v-for="ql in quickLinks"
                :key="ql.href"
                :href="ql.href"
                :class="['group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 transition-all hover:shadow-md', ql.color]"
            >
                <div class="flex items-center gap-3">
                    <span :class="['w-9 h-9 rounded-xl flex items-center justify-center text-lg transition-colors', ql.bg, ql.text]">{{ ql.icon }}</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ ql.label }}</p>
                        <p class="text-xs text-gray-400">Go →</p>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>
