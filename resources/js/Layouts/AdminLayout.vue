<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const showSidebar = ref(false);

const logoutForm = useForm({});

function logout() {
    logoutForm.post('/logout');
}

const navItems = [
    { label: 'Dashboard', href: '/admin/dashboard' },
    { label: 'Shops', href: '/admin/shops' },
    { label: 'Owners', href: '/admin/owners' },
    { label: 'Products', href: '/admin/products' },
];

function isActive(href: string): boolean {
    return page.url.startsWith(href);
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Top header -->
        <header class="bg-white shadow-sm z-20 flex-shrink-0">
            <div class="flex items-center justify-between px-4 py-3 sm:px-6">
                <div class="flex items-center gap-3">
                    <!-- Mobile hamburger -->
                    <button
                        class="md:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100"
                        @click="showSidebar = !showSidebar"
                        aria-label="Toggle sidebar"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!showSidebar" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <span class="font-semibold text-gray-800">Admin Panel</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">{{ page.props.auth.user.name }}</span>
                    <button
                        @click="logout"
                        class="text-sm text-red-600 hover:text-red-800 font-medium"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar overlay on mobile -->
            <div
                v-if="showSidebar"
                class="fixed inset-0 z-10 bg-black/30 md:hidden"
                @click="showSidebar = false"
            />

            <!-- Sidebar -->
            <aside
                :class="[
                    'fixed inset-y-0 left-0 z-20 w-64 bg-white shadow-md transform transition-transform duration-200 md:relative md:translate-x-0 md:flex-shrink-0',
                    showSidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
                ]"
            >
                <nav class="mt-16 md:mt-0 p-4 space-y-1">
                    <Link
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        :class="[
                            'block px-4 py-2 rounded-md text-sm font-medium transition-colors',
                            isActive(item.href)
                                ? 'bg-indigo-50 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100'
                        ]"
                        @click="showSidebar = false"
                    >
                        {{ item.label }}
                    </Link>
                </nav>
            </aside>

            <!-- Main content -->
            <main class="flex-1 overflow-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
