<script setup lang="ts">
import { ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
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
                    <RouterLink to="/" class="flex items-center gap-2 shrink-0">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                            C
                        </div>
                        <span class="font-bold text-gray-900 text-lg">CNS Commerce</span>
                    </RouterLink>

                    <!-- Desktop nav -->
                    <nav class="hidden md:flex items-center gap-1">
                        <RouterLink
                            v-for="link in navLinks"
                            :key="link.label"
                            :to="link.href"
                            class="px-4 py-1.5 text-sm font-medium rounded-full text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                        >
                            {{ link.label }}
                        </RouterLink>
                    </nav>

                    <!-- Right actions -->
                    <div class="flex items-center gap-3">
                        <button class="hidden md:flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                            </svg>
                        </button>

                        <!-- Admin/Owner portal link -->
                        <template v-if="authStore.user">
                            <RouterLink
                                :to="authStore.user.role === 'admin' ? '/admin/dashboard' : '/owner/dashboard'"
                                class="text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-full transition-colors"
                            >
                                Dashboard
                            </RouterLink>
                        </template>
                        <template v-else>
                            <RouterLink
                                to="/login"
                                class="flex items-center gap-1.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-full transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Admin Login
                            </RouterLink>
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
                    <RouterLink
                        v-for="link in navLinks"
                        :key="link.label"
                        :to="link.href"
                        class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600"
                        @click="mobileOpen = false"
                    >
                        {{ link.label }}
                    </RouterLink>
                </div>
            </div>
        </header>

        <!-- ─── MAIN CONTENT ──────────────────────────────────── -->
        <main class="flex-1">
            <slot />
        </main>
        <!-- ─── FOOTER ───────────────────────────────────────── -->
        <footer class="bg-gray-950 text-gray-400 relative">
            <!-- Top gradient edge -->
            <div class="h-px w-full bg-gradient-to-r from-transparent via-indigo-500/40 to-transparent" />

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-14 pb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

                    <!-- Brand col -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <RouterLink to="/" class="flex items-center gap-2.5 mb-4 w-fit">
                            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-base shadow-lg shadow-indigo-600/30">C</div>
                            <span class="font-bold text-white text-lg tracking-tight">CNS Commerce</span>
                        </RouterLink>
                        <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                            Connecting shoppers with independent shop owners. Quality products, competitive prices, all in one place.
                        </p>
                        <!-- Social icons with real SVGs -->
                        <div class="flex gap-2.5 mt-5">
                            <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-indigo-600 flex items-center justify-center text-gray-400 hover:text-white transition-all hover:shadow-md hover:shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a href="#" aria-label="Twitter/X" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-indigo-600 flex items-center justify-center text-gray-400 hover:text-white transition-all hover:shadow-md hover:shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-indigo-600 flex items-center justify-center text-gray-400 hover:text-white transition-all hover:shadow-md hover:shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                            </a>
                            <a href="#" aria-label="GitHub" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-indigo-600 flex items-center justify-center text-gray-400 hover:text-white transition-all hover:shadow-md hover:shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Quick Links</h4>
                        <ul class="space-y-3">
                            <li v-for="l in footerQuickLinks" :key="l">
                                <RouterLink to="/" class="text-sm text-gray-500 hover:text-white transition-colors flex items-center gap-1.5 group">
                                    <span class="w-1 h-1 rounded-full bg-gray-600 group-hover:bg-indigo-400 transition-colors" />
                                    {{ l }}
                                </RouterLink>
                            </li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Resources</h4>
                        <ul class="space-y-3">
                            <li v-for="r in footerResources" :key="r">
                                <a href="#" class="text-sm text-gray-500 hover:text-white transition-colors flex items-center gap-1.5 group">
                                    <span class="w-1 h-1 rounded-full bg-gray-600 group-hover:bg-indigo-400 transition-colors" />
                                    {{ r }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Stay Connected</h4>
                        <p class="text-sm text-gray-500 mb-4 leading-relaxed">Get the latest updates and offers directly to your inbox.</p>
                        <form class="space-y-2" @submit.prevent>
                            <input
                                type="email"
                                placeholder="your@email.com"
                                class="w-full text-sm bg-gray-800/80 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-300 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            />
                            <button
                                type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm shadow-indigo-600/20"
                            >
                                Subscribe →
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Divider -->
                <div class="mt-12 pt-6 border-t border-gray-800/80 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-600">
                        © {{ new Date().getFullYear() }} CNS Commerce. All rights reserved.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">Privacy</a>
                        <a href="#" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">Terms</a>
                        <p class="text-xs text-gray-600">
                            Built with <span class="text-red-500">♥</span> using Laravel + Vue.js
                        </p>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</template>
