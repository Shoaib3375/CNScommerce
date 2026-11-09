<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import type { PaginatedData, Shop, ShopProduct } from '@/types';

defineOptions({ layout: StorefrontLayout });

defineProps<{
    shops: PaginatedData<Shop>;
    featured: ShopProduct[];
}>();

const search = ref('');

function onSearch() {
    if (search.value.trim()) {
        router.get('/', { search: search.value });
    }
}

function imgSrc(path: string | null | undefined): string | null {
    if (!path) return null;
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path}`;
}

const stats = [
    { icon: '🏪', value: '5+',   label: 'Shops' },
    { icon: '📦', value: '500+', label: 'Products' },
    { icon: '👥', value: '10K+', label: 'Happy Customers' },
];

const features = [
    { icon: '🏬', title: 'Multiple Shops',      desc: 'Explore products from various shops' },
    { icon: '🛡️', title: 'Trusted Platform',    desc: 'Secure and reliable shopping experience' },
    { icon: '💰', title: 'Shop-specific Pricing', desc: 'Get the best prices from each shop' },
];
</script>

<template>
    <div>
        <!-- ─── HERO ──────────────────────────────────────────────── -->
        <section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16 px-4">
            <div class="mx-auto max-w-7xl flex flex-col md:flex-row items-center gap-10">
                <!-- Left copy -->
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block mb-4 text-xs font-semibold text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">
                        🛍️ A multi-shop marketplace
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                        Discover products.<br />
                        <span class="text-indigo-600">Explore shops.</span>
                    </h1>
                    <p class="mt-4 text-gray-500 text-lg">
                        Find products from independent shops, all in one place.
                    </p>

                    <!-- Search bar -->
                    <form class="mt-6 flex items-center gap-2 max-w-md mx-auto md:mx-0" @submit.prevent="onSearch">
                        <div class="flex-1 flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2 shadow-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                            </svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search products or shops…"
                                class="flex-1 text-sm outline-none bg-transparent"
                            />
                        </div>
                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors"
                        >
                            Search
                        </button>
                    </form>

                    <!-- Quick stats -->
                    <div class="mt-8 flex flex-wrap gap-5 justify-center md:justify-start">
                        <div
                            v-for="s in stats"
                            :key="s.label"
                            class="flex items-center gap-2 text-sm text-gray-600"
                        >
                            <span>{{ s.icon }}</span>
                            <span class="font-bold text-gray-900">{{ s.value }}</span>
                            <span>{{ s.label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right illustration -->
                <div class="flex-1 flex justify-center">
                    <div class="relative w-72 h-72 sm:w-96 sm:h-96">
                        <div class="absolute inset-0 bg-indigo-200 rounded-full opacity-30 blur-3xl"></div>
                        <div class="relative z-10 flex items-center justify-center h-full">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded-2xl shadow-lg p-4 flex flex-col items-center gap-2">
                                    <span class="text-4xl">🏪</span>
                                    <span class="text-xs font-semibold text-gray-700">Shop Local</span>
                                </div>
                                <div class="bg-indigo-600 rounded-2xl shadow-lg p-4 flex flex-col items-center gap-2 mt-6">
                                    <span class="text-4xl">🛒</span>
                                    <span class="text-xs font-semibold text-white">Best Deals</span>
                                </div>
                                <div class="bg-white rounded-2xl shadow-lg p-4 flex flex-col items-center gap-2 -mt-4">
                                    <span class="text-4xl">📦</span>
                                    <span class="text-xs font-semibold text-gray-700">500+ Items</span>
                                </div>
                                <div class="bg-yellow-400 rounded-2xl shadow-lg p-4 flex flex-col items-center gap-2 mt-2">
                                    <span class="text-4xl">⭐</span>
                                    <span class="text-xs font-semibold text-gray-800">Top Rated</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ─── EXPLORE SHOPS ─────────────────────────────────────── -->
        <section class="py-14 px-4 bg-white">
            <div class="mx-auto max-w-7xl">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <div class="w-10 h-1 bg-indigo-600 rounded mb-3"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Explore Our Shops</h2>
                        <p class="text-sm text-gray-500 mt-1">Discover top-rated shops on our platform.</p>
                    </div>
                    <Link
                        href="/"
                        class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-800 border border-indigo-200 px-4 py-2 rounded-lg hover:bg-indigo-50 transition-colors"
                    >
                        View All Shops →
                    </Link>
                </div>

                <div v-if="shops.data.length === 0" class="text-center py-12 text-gray-400">
                    No active shops yet. Check back soon!
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="shop in shops.data"
                        :key="shop.id"
                        :href="`/shop/${shop.slug}`"
                        class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
                    >
                        <!-- Shop banner / placeholder -->
                        <div class="h-32 bg-gradient-to-br from-indigo-100 to-blue-50 relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center text-5xl opacity-30 group-hover:opacity-50 transition-opacity">
                                🏪
                            </div>
                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 text-xs font-semibold bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                            </span>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                                    {{ shop.name.charAt(0) }}
                                </div>
                                <h3 class="font-semibold text-gray-900 text-sm truncate">{{ shop.name }}</h3>
                            </div>
                            <p class="text-xs text-gray-400 line-clamp-2 mb-3 min-h-[2rem]">
                                {{ shop.description ?? 'Visit this shop to explore products.' }}
                            </p>
                            <span class="inline-block text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                Visit Shop →
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ─── FEATURED PRODUCTS ─────────────────────────────────── -->
        <section class="py-14 px-4 bg-gray-50">
            <div class="mx-auto max-w-7xl">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <div class="w-10 h-1 bg-indigo-600 rounded mb-3"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Featured Products</h2>
                        <p class="text-sm text-gray-500 mt-1">Handpicked products from our shops.</p>
                    </div>
                </div>

                <div v-if="featured.length === 0" class="text-center py-12 text-gray-400">
                    No featured products yet.
                </div>

                <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <Link
                        v-for="sp in featured"
                        :key="sp.id"
                        :href="`/shop/${sp.shop?.slug}/products/${sp.product?.slug}`"
                        class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
                    >
                        <!-- Product image -->
                        <div class="aspect-square overflow-hidden bg-gray-100 relative">
                            <img
                                v-if="imgSrc(sp.thumbnail ?? sp.product?.thumbnail)"
                                :src="imgSrc(sp.thumbnail ?? sp.product?.thumbnail)!"
                                :alt="sp.product?.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-3">
                            <h3 class="text-xs font-semibold text-gray-900 truncate">{{ sp.product?.name }}</h3>
                            <div class="mt-1 flex items-center gap-1">
                                <div class="w-4 h-4 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs shrink-0">
                                    🏪
                                </div>
                                <span class="text-xs text-gray-400 truncate">{{ sp.shop?.name }}</span>
                            </div>
                            <p class="mt-2 text-sm font-bold text-indigo-600">${{ sp.price }}</p>
                            <button
                                type="button"
                                class="mt-2 w-full text-xs py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg transition-colors flex items-center justify-center gap-1"
                            >
                                🛒 View Product
                            </button>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ─── WHY US ─────────────────────────────────────────────── -->
        <section class="py-14 px-4 bg-gradient-to-r from-indigo-600 to-blue-600">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Why CNS Commerce?</h2>
                        <p class="mt-2 text-indigo-200 text-sm">A better way to shop online</p>
                    </div>
                    <div
                        v-for="f in features"
                        :key="f.title"
                        class="flex flex-col items-center text-center p-4 bg-white/10 rounded-2xl"
                    >
                        <span class="text-3xl mb-2">{{ f.icon }}</span>
                        <h3 class="text-white font-semibold text-sm">{{ f.title }}</h3>
                        <p class="text-indigo-200 text-xs mt-1">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
