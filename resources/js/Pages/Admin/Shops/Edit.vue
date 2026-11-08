<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { User, Shop } from '@/types';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    shop: Shop;
    owners: User[];
}>();

const form = useForm({
    name: props.shop.name,
    slug: props.shop.slug,
    description: props.shop.description ?? '',
    status: props.shop.status,
    owner_id: props.shop.owner?.id ?? '' as string | number,
});

function submit() {
    form.patch(`/admin/shops/${props.shop.id}`);
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/admin/shops" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">Edit Shop</h1>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.slug" class="mt-1 text-xs text-red-600">{{ form.errors.slug }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Owner -->
                <div>
                    <label for="owner_id" class="block text-sm font-medium text-gray-700">Owner</label>
                    <select
                        id="owner_id"
                        v-model="form.owner_id"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="" disabled>Select an owner</option>
                        <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                            {{ owner.name }} ({{ owner.email }})
                        </option>
                    </select>
                    <p v-if="form.errors.owner_id" class="mt-1 text-xs text-red-600">{{ form.errors.owner_id }}</p>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3">
                    <label class="block text-sm font-medium text-gray-700">Active</label>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="form.status"
                        :class="[
                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                            form.status ? 'bg-indigo-600' : 'bg-gray-300'
                        ]"
                        @click="form.status = !form.status"
                    >
                        <span
                            :class="[
                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                form.status ? 'translate-x-6' : 'translate-x-1'
                            ]"
                        />
                    </button>
                    <p v-if="form.errors.status" class="text-xs text-red-600">{{ form.errors.status }}</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <Link href="/admin/shops" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
