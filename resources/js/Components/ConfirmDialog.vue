<script setup lang="ts">
defineProps<{
    show: boolean;
    message?: string;
}>();

const emit = defineEmits<{
    confirm: [];
    cancel: [];
}>();
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                role="dialog"
                aria-modal="true"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-black/40"
                    @click="emit('cancel')"
                />

                <!-- Dialog -->
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Action</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        {{ message ?? 'Are you sure you want to proceed? This action cannot be undone.' }}
                    </p>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            @click="emit('cancel')"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            @click="emit('confirm')"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
