<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import type { User } from '@/types';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ owner: User }>();

const form = useForm({ name: props.owner.name, email: props.owner.email, password: '', password_confirmation: '' });
function submit() { form.patch(`/admin/owners/${props.owner.id}`); }
</script>

<template>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-7">
            <Link href="/admin/owners" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 transition-colors text-sm">←</Link>
            <div>
                <div class="w-6 h-1 bg-indigo-600 rounded mb-1" />
                <h1 class="text-xl font-bold text-gray-900">Edit Owner</h1>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                <input v-model="form.name" type="text" required autocomplete="name" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                <input v-model="form.email" type="email" required autocomplete="email" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-gray-50 focus:bg-white" />
                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
            </div>
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-xs font-semibold text-amber-700 mb-3">Change Password <span class="font-normal text-amber-600">(leave blank to keep current)</span></p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <input v-model="form.password" type="password" autocomplete="new-password" placeholder="New password" class="w-full rounded-xl border border-amber-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white" />
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <input v-model="form.password_confirmation" type="password" autocomplete="new-password" placeholder="Confirm password" class="w-full rounded-xl border border-amber-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <Link href="/admin/owners" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</Link>
                <button type="button" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm disabled:opacity-50 transition-colors" @click="submit">
                    {{ form.processing ? 'Saving…' : 'Save Changes' }}
                </button>
            </div>
        </div>
    </div>
</template>
