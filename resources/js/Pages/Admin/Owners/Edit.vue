<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { User } from '@/types';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
    owner: User;
}>();

const form = useForm({
    name: props.owner.name,
    email: props.owner.email,
    password: '',
    password_confirmation: '',
});

function submit() {
    form.patch(`/admin/owners/${props.owner.id}`);
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/admin/owners" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">Edit Owner</h1>
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
                        autocomplete="name"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autocomplete="email"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                </div>

                <!-- Password (optional) -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <Link href="/admin/owners" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
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
