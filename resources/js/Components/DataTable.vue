<script setup lang="ts">
interface Column {
    key: string;
    label: string;
}

const props = defineProps<{
    columns: Column[];
    rows: Record<string, unknown>[];
    searchable?: boolean;
}>();

const emit = defineEmits<{
    search: [value: string];
}>();

function onSearch(event: Event) {
    emit('search', (event.target as HTMLInputElement).value);
}
</script>

<template>
    <div>
        <div v-if="props.searchable" class="mb-4">
            <input
                type="text"
                placeholder="Search..."
                class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                @input="onSearch"
            />
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="col in props.columns"
                            :key="col.key"
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        >
                            {{ col.label }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="props.rows.length === 0">
                        <td :colspan="props.columns.length + 1" class="px-4 py-8 text-center text-sm text-gray-500">
                            No records found.
                        </td>
                    </tr>
                    <tr v-for="(row, index) in props.rows" :key="index" class="hover:bg-gray-50">
                        <td
                            v-for="col in props.columns"
                            :key="col.key"
                            class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap"
                        >
                            <slot :name="`cell-${col.key}`" :row="row">
                                {{ row[col.key] }}
                            </slot>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <slot name="actions" :row="row" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
