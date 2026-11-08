<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center">
                <Link href="/" class="text-xl font-bold text-indigo-600 hover:text-indigo-800">
                    CNS Commerce
                </Link>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
                &copy; {{ new Date().getFullYear() }} CNS Commerce. All rights reserved.
            </div>
        </footer>
    </div>
</template>
