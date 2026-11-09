<script setup lang="ts">
import { RouterLink } from 'vue-router';
import type { PaginatedData } from '@/types';

defineProps<{
    links: PaginatedData<unknown>['links'];
}>();
</script>

<template>
    <nav v-if="links.length > 3" class="flex items-center justify-center gap-1 mt-6" aria-label="Pagination">
        <template v-for="link in links" :key="link.label">
            <span
                v-if="link.url === null"
                class="px-3 py-1.5 text-sm rounded border border-gray-200 text-gray-400 cursor-default"
                v-html="link.label"
            />
            <RouterLink
                v-else
                :to="link.url"
                :class="[
                    'px-3 py-1.5 text-sm rounded border transition-colors',
                    link.active
                        ? 'bg-indigo-600 text-white border-indigo-600'
                        : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
