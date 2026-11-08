<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import type { PaginatedData, Shop } from '@/types';

defineOptions({ layout: StorefrontLayout });

defineProps<{
    shops: PaginatedData<Shop>;
}>();
</script>

<template>
    <div>
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Welcome to CNS Commerce</h1>
            <p class="mt-2 text-gray-500">Discover products from our shops below.</p>
        </div>

        <div v-if="shops.data.length === 0" class="text-center py-12 text-gray-500">
            No active shops available yet. Check back soon!
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="shop in shops.data"
                :key="shop.id"
                class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow"
            >
                <h2 class="text-lg font-semibold text-gray-900">{{ shop.name }}</h2>
                <p v-if="shop.description" class="mt-2 text-sm text-gray-500 line-clamp-2">
                    {{ shop.description }}
                </p>
                <p v-else class="mt-2 text-sm text-gray-400 italic">No description.</p>
                <div class="mt-4">
                    <Link
                        :href="`/shop/${shop.slug}`"
                        class="inline-block text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md transition-colors"
                    >
                        Visit Shop →
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
