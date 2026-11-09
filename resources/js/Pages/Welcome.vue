<script setup lang="ts">
import { RouterLink } from 'vue-router';
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to CNS Commerce</h1>
            <RouterLink to="/" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Browse the Storefront →
            </RouterLink>
        </div>
    </div>
</template>
