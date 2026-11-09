<script setup lang="ts">
defineProps<{
    active: boolean;
}>();
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold',
            active
                ? 'bg-emerald-100 text-emerald-700'
                : 'bg-red-100 text-red-600'
        ]"
    >
        <span
            :class="[
                'w-1.5 h-1.5 rounded-full',
                active ? 'bg-emerald-500' : 'bg-red-400'
            ]"
        />
        {{ active ? 'Active' : 'Inactive' }}
    </span>
</template>
