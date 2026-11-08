<script setup lang="ts">
defineProps<{
    appName?: string;
    pageTitle?: string;
}>();

const emit = defineEmits<{
    'toggle-sidebar': [];
}>();
</script>

<template>
    <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <!-- Mobile hamburger -->
                <button
                    class="p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    aria-label="Toggle sidebar"
                    @click="emit('toggle-sidebar')"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span v-if="appName" class="font-semibold text-gray-800">{{ appName }}</span>
            </div>
            <h1 v-if="pageTitle" class="text-sm font-medium text-gray-600">{{ pageTitle }}</h1>
        </div>
    </header>
</template>
