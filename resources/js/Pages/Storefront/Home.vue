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
        <section class="py-20 px-4 bg-gray-950 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent" />
                <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl" />
                <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl" />
            </div>

            <div class="mx-auto max-w-7xl relative">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Left: copy -->
                    <div>
                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-1.5 rounded-full mb-6">
                            <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse" />
                            Why choose us
                        </span>
                        <h2 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                            A smarter way<br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400">to shop online.</span>
                        </h2>
                        <p class="mt-5 text-gray-400 text-lg leading-relaxed max-w-md">
                            CNS Commerce connects you with independent shops offering unique products at competitive shop-specific prices.
                        </p>

                        <!-- Stats row — different metrics from the hero -->
                        <div class="mt-10 grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <p class="text-3xl font-extrabold text-white">99%</p>
                                <p class="text-xs text-gray-500 mt-1">Satisfaction Rate</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-extrabold text-white">24/7</p>
                                <p class="text-xs text-gray-500 mt-1">Platform Uptime</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-extrabold text-white">0%</p>
                                <p class="text-xs text-gray-500 mt-1">Hidden Fees</p>
                            </div>
                        </div>

                        <Link href="/" class="mt-10 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg shadow-indigo-600/20">
                            Explore All Shops
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </Link>
                    </div>

                    <!-- Right: feature cards -->
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Card 1 -->
                        <div class="flex items-start gap-5 bg-white/5 hover:bg-white/8 border border-white/10 rounded-2xl p-6 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center shrink-0 group-hover:bg-indigo-500/30 transition-colors">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-base">Multiple Independent Shops</h3>
                                <p class="text-gray-400 text-sm mt-1.5 leading-relaxed">Browse products from dozens of curated shops, each with their own unique catalog and pricing.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="flex items-start gap-5 bg-white/5 hover:bg-white/8 border border-white/10 rounded-2xl p-6 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center shrink-0 group-hover:bg-blue-500/30 transition-colors">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-base">Trusted & Secure Platform</h3>
                                <p class="text-gray-400 text-sm mt-1.5 leading-relaxed">Every shop is verified. Your data is protected and transactions are handled with care.</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="flex items-start gap-5 bg-white/5 hover:bg-white/8 border border-white/10 rounded-2xl p-6 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0 group-hover:bg-emerald-500/30 transition-colors">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-base">Shop-specific Pricing</h3>
                                <p class="text-gray-400 text-sm mt-1.5 leading-relaxed">Each shop sets its own prices. Compare listings and always get the best deal for your budget.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
