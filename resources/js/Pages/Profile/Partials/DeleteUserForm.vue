<script setup lang="ts">
import { reactive, ref, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from '@/axios';

const authStore = useAuthStore();
const router = useRouter();
const confirmingUserDeletion = ref(false);
const passwordInput = ref<InstanceType<typeof TextInput> | null>(null);

const form = reactive({
    password: '',
    processing: false,
    errors: {} as Record<string, string[]>,
});

function confirmUserDeletion() {
    confirmingUserDeletion.value = true;
    nextTick(() => {
        // focus is managed by the input ref
    });
}

async function deleteUser() {
    form.processing = true;
    form.errors = {};
    try {
        await axios.delete('/profile', { data: { password: form.password } });
        authStore.user = null;
        closeModal();
        router.push('/');
    } catch (e: unknown) {
        const err = e as { response?: { status?: number; data?: { errors?: Record<string, string[]> } } };
        if (err.response?.status === 422) {
            form.errors = err.response.data?.errors ?? {};
        }
    } finally {
        form.processing = false;
        form.password = '';
    }
}

function closeModal() {
    confirmingUserDeletion.value = false;
    form.errors = {};
    form.password = '';
}
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password?.[0]" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
