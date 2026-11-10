<script setup lang="ts">
import { computed, reactive } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from '@/axios';

const authStore = useAuthStore();
const router = useRouter();

const form = reactive({
    processing: false,
    status: '',
});

const verificationLinkSent = computed(() => form.status === 'verification-link-sent');

async function submit() {
    form.processing = true;
    try {
        const r = await axios.post('/email/verification-notification');
        form.status = r.data.status ?? 'verification-link-sent';
    } finally {
        form.processing = false;
    }
}

async function logout() {
    await authStore.logout();
    router.push('/login');
}
</script>

<template>
    <GuestLayout>
        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <button
                    type="button"
                    @click="logout"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Log Out
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
