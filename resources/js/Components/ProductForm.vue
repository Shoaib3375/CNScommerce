<script setup lang="ts">
import ImageUpload from '@/Components/ImageUpload.vue';

interface ProductFormData {
    name: string;
    description: string;
    base_price: string | number;
    thumbnail: File | null;
    [key: string]: unknown;
}

interface FormLike extends ProductFormData {
    errors: Record<string, string>;
}

const props = defineProps<{
    form: FormLike;
    processing: boolean;
}>();

const emit = defineEmits<{
    submit: [];
}>();
</script>

<template>
    <form @submit.prevent="emit('submit')" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
                id="name"
                v-model="props.form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <p v-if="props.form.errors.name" class="mt-1 text-xs text-red-600">{{ props.form.errors.name }}</p>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                id="description"
                v-model="props.form.description"
                rows="4"
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <p v-if="props.form.errors.description" class="mt-1 text-xs text-red-600">{{ props.form.errors.description }}</p>
        </div>

        <!-- Base Price -->
        <div>
            <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price</label>
            <input
                id="base_price"
                v-model="props.form.base_price"
                type="number"
                min="0"
                step="0.01"
                required
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <p v-if="props.form.errors.base_price" class="mt-1 text-xs text-red-600">{{ props.form.errors.base_price }}</p>
        </div>

        <!-- Thumbnail -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
            <ImageUpload
                :existing-url="typeof props.form.thumbnail === 'string' ? props.form.thumbnail : null"
                class="mt-1"
                @update:model-value="(file) => { props.form.thumbnail = file; }"
            />
            <p v-if="props.form.errors.thumbnail" class="mt-1 text-xs text-red-600">{{ props.form.errors.thumbnail }}</p>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button
                type="submit"
                :disabled="props.processing"
                class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                {{ props.processing ? 'Saving...' : 'Save' }}
            </button>
        </div>
    </form>
</template>
