<script setup lang="ts">
defineProps<{
    stats: { shops: number; owners: number; products: number; activeAssignments: number };
}>();
</script>

<template>
    <div>
        <h1>Admin Dashboard</h1>
    </div>
</template>
