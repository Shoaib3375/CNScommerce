<script setup lang="ts">
defineProps<{
    shop: object | null;
    stats: { assignedProducts: number; activeProducts: number; myProducts: number };
}>();
</script>
<template><div><h1>Owner Dashboard</h1></div></template>
