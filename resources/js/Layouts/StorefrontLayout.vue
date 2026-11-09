<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const mobileOpen = ref(false);

const navLinks = [
    { label: 'Home',    href: '/' },
    { label: 'Shops',   href: '/' },
    { label: 'About',   href: '/' },
    { label: 'Contact', href: '/' },
];

const footerQuickLinks = ['Home', 'Shops', 'About', 'Contact'];
const footerResources  = ['FAQ', 'Privacy Policy', 'Terms of Service', 'Support'];
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">

        <!-- ─── NAVBAR ──────────────────────────────────────────── -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-2 shrink-0">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                            C
                        </div>
                        <span class="font-bold text-gray-900 text-lg">CNS Commerce</span>
                    </Link>

                    <!-- Desktop nav -->
                    <nav class="hidden md:flex items-center gap-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.label"
                            :href="link.href"
                            class="px-4 py-1.5 text-sm font-medium rounded-full text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>

                    <!-- Right actions -->
                    <div class="flex items-center gap-3">
                        <button class="hidden md:flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                            </svg>
                        </button>

                        <!-- Admin/Owner portal link -->
                        <template v-if="page.props.auth?.user">
                            <Link
                                :href="page.props.auth.user.role === 'admin' ? '/admin/dashboard' : '/owner/dashboard'"
                                class="text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-full transition-colors"
                            >
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                href="/login"
                                class="flex items-center gap-1.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-full transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Admin Login
                            </Link>
                        </template>

                        <!-- Mobile hamburger -->
                        <button
                            class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100"
                            @click="mobileOpen = !mobileOpen"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile nav dropdown -->
                <div v-if="mobileOpen" class="md:hidden pb-4 border-t border-gray-100 pt-3 space-y-1">
                    <Link
                        v-for="link in navLinks"
                        :key="link.label"
                        :href="link.href"
                        class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600"
                        @click="mobileOpen = false"
                    >
                        {{ link.label }}
                    </Link>
                </div>
            </div>
        </header>

        <!-- ─── MAIN CONTENT ──────────────────────────────────── -->
        <main class="flex-1">
            <slot />
        </main>
        <!-- ─── FOOTER ───────────────────────────────────────── -->
        <footer class="bg-gray-900 text-gray-300">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- Brand -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">C</div>
                            <span class="font-bold text-white text-lg">CNS Commerce</span>
                        </div>
                        <p class="text-sm text-gray-400">Shop Smart. Shop Local.</p>
                        <div class="flex gap-3 mt-4">
                            <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-colors text-sm">f</a>
                            <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-colors text-sm">▶</a>
                            <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-colors text-sm">𝕏</a>
                            <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-colors text-sm">⌥</a>
                        </div>
                    </div>

                    <!-- Quick links -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li v-for="l in footerQuickLinks" :key="l">
                                <Link href="/" class="text-sm text-gray-400 hover:text-white transition-colors">{{ l }}</Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Resources</h4>
                        <ul class="space-y-2">
                            <li v-for="r in footerResources" :key="r">
                                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">{{ r }}</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Stay Connected</h4>
                        <p class="text-sm text-gray-400 mb-3">Subscribe to our newsletter</p>
                        <form class="flex gap-2" @submit.prevent>
                            <input
                                type="email"
                                placeholder="Your email address"
                                class="flex-1 text-sm bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shrink-0"
                            >
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p class="text-xs text-gray-500">
                        © {{ new Date().getFullYear() }} CNS Commerce. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-500">
                        Built with ❤️ using Laravel + Vue.js
                    </p>
                </div>
            </div>
        </footer>

    </div>
</template>
