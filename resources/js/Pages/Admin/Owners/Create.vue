<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/admin/owners');
}
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/admin/owners" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</Link>
            <h1 class="text-2xl font-bold text-gray-900">Add Shop Owner</h1>
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

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
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
                        {{ form.processing ? 'Creating...' : 'Create Owner' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
