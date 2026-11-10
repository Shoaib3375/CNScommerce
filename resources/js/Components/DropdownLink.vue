<script setup lang="ts">
import { RouterLink } from 'vue-router';

const props = defineProps<{
    href: string;
    as?: string;
}>();
</script>

<template>
    <button
        v-if="props.as === 'button'"
        type="button"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </button>
    <RouterLink
        v-else
        :to="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </RouterLink>
</template>
