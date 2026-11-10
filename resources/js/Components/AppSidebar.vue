<script setup lang="ts">
import { RouterLink, useRoute } from 'vue-router';

interface NavItem {
    label: string;
    href: string;
    icon?: string;
}

defineProps<{
    navItems: NavItem[];
}>();

const route = useRoute();

function isActive(href: string): boolean {
    return route.path.startsWith(href);
}
</script>

<template>
    <nav class="space-y-1">
        <RouterLink
            v-for="item in navItems"
            :key="item.href"
            :to="item.href"
            :class="[
                'flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors',
                isActive(item.href)
                    ? 'bg-indigo-50 text-indigo-700'
                    : 'text-gray-700 hover:bg-gray-100'
            ]"
        >
            <span v-if="item.icon" class="text-base" aria-hidden="true">{{ item.icon }}</span>
            {{ item.label }}
        </RouterLink>
    </nav>
</template>
