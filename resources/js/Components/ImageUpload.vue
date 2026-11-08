<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue?: File | null;
    existingUrl?: string | null;
}>();

const emit = defineEmits<{
    'update:modelValue': [file: File | null];
}>();

const previewUrl = ref<string | null>(props.existingUrl ?? null);
const errorMsg = ref<string | null>(null);

watch(() => props.existingUrl, (url) => {
    if (!props.modelValue) {
        previewUrl.value = url ?? null;
    }
});

function onFileChange(event: Event) {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;
    errorMsg.value = null;

    if (!file) {
        previewUrl.value = props.existingUrl ?? null;
        emit('update:modelValue', null);
        return;
    }

    const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) {
        errorMsg.value = 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
        input.value = '';
        return;
    }

    const maxBytes = 2 * 1024 * 1024; // 2MB
    if (file.size > maxBytes) {
        errorMsg.value = 'Image must be smaller than 2MB.';
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        previewUrl.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
    emit('update:modelValue', file);
}
</script>

<template>
    <div>
        <div v-if="previewUrl" class="mb-2">
            <img
                :src="previewUrl"
                alt="Preview"
                class="h-32 w-32 object-cover rounded-md border border-gray-200"
            />
        </div>
        <label class="block">
            <span class="sr-only">Choose image</span>
            <input
                type="file"
                accept="image/jpeg,image/jpg,image/png,image/webp"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                @change="onFileChange"
            />
        </label>
        <p v-if="errorMsg" class="mt-1 text-xs text-red-600">{{ errorMsg }}</p>
        <p class="mt-1 text-xs text-gray-400">Accepted: JPG, PNG, WEBP. Max 2MB.</p>
    </div>
</template>
