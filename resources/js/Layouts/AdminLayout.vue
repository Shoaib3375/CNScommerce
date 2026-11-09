<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const sidebarOpen = ref(false);
const logoutForm = useForm({});

function logout() {
    logoutForm.post('/logout');
}

const navItems = [
    { label: 'Dashboard', href: '/admin/dashboard', icon: '📊' },
    { label: 'Shops',     href: '/admin/shops',     icon: '🏪' },
    { label: 'Owners',    href: '/admin/owners',    icon: '👤' },
    { label: 'Products',  href: '/admin/products',  icon: '📦' },
];

function isActive(href: string): boolean {
    return page.url.startsWith(href);
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">

        <!-- ── Sidebar overlay (mobile) ── -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- ── Sidebar ── -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 w-64 flex flex-col bg-gradient-to-b from-indigo-900 to-indigo-800 transform transition-transform duration-300 lg:relative lg:translate-x-0 lg:flex-shrink-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-indigo-700/50">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-lg shadow">
                    C
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-none">CNS Commerce</p>
                    <p class="text-indigo-300 text-xs mt-0.5">Admin Panel</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all',
                        isActive(item.href)
                            ? 'bg-white/20 text-white shadow-inner'
                            : 'text-indigo-200 hover:bg-white/10 hover:text-white'
                    ]"
                    @click="sidebarOpen = false"
                >
                    <span class="text-base">{{ item.icon }}</span>
                    {{ item.label }}
                </Link>
            </nav>

            <!-- User footer -->
            <div class="px-4 py-4 border-t border-indigo-700/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                        {{ page.props.auth?.user?.name?.charAt(0) ?? 'A' }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-white text-xs font-semibold truncate">{{ page.props.auth?.user?.name }}</p>
                        <p class="text-indigo-300 text-xs truncate">Administrator</p>
                    </div>
                </div>
                <button
                    @click="logout"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-indigo-200 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </div>
        </aside>

        <!-- ── Main area ── -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top bar -->
            <header class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between px-4 sm:px-6 h-14">
                    <div class="flex items-center gap-3">
                        <button
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100"
                            @click="sidebarOpen = !sidebarOpen"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <!-- Breadcrumb hint -->
                        <span class="text-sm text-gray-400 hidden sm:block">Admin</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <Link
                            href="/"
                            class="text-xs text-gray-500 hover:text-indigo-600 transition-colors"
                        >
                            ← View Storefront
                        </Link>
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ page.props.auth?.user?.name?.charAt(0) ?? 'A' }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-5 sm:p-7 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
