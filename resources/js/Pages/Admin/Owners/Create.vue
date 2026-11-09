<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });
function submit() { form.post('/admin/owners'); }
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-7">
            <Link href="/admin/owners" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 transition-colors text-sm">←</Link>
            <div>
                <div class="w-6 h-1 bg-indigo-600 rounded mb-1" />
                <h1 class="text-xl font-bold text-gray-900">Add Shop Owner</h1>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                <input v-model="form.name" type="text" required autocomplete="name" placeholder="John Smith" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                <input v-model="form.email" type="email" required autocomplete="email" placeholder="owner@example.com" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input v-model="form.password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                    <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <Link href="/admin/owners" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</Link>
                <button type="button" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm disabled:opacity-50 transition-colors" @click="submit">
                    {{ form.processing ? 'Creating…' : 'Create Owner' }}
                </button>
            </div>
        </div>
    </div>
</template>
