<script setup lang="ts">
import { ref } from 'vue';

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

const searchValue = ref('');

function onSearch(event: Event) {
    searchValue.value = (event.target as HTMLInputElement).value;
    emit('search', searchValue.value);
}
</script>

<template>
    <div>
        <!-- Search bar -->
        <div v-if="props.searchable" class="mb-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                </svg>
                <input
                    type="text"
                    :value="searchValue"
                    placeholder="Search…"
                    class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    @input="onSearch"
                />
            </div>
        </div>

        <!-- Table card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-50 to-gray-50">
                            <th
                                v-for="col in props.columns"
                                :key="col.key"
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                {{ col.label }}
                            </th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="props.rows.length === 0">
                            <td
                                :colspan="props.columns.length + 1"
                                class="px-5 py-12 text-center"
                            >
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm">No records found.</span>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="(row, index) in props.rows"
                            :key="index"
                            class="hover:bg-indigo-50/30 transition-colors"
                        >
                            <td
                                v-for="col in props.columns"
                                :key="col.key"
                                class="px-5 py-3.5 text-sm text-gray-700 whitespace-nowrap"
                            >
                                <slot :name="`cell-${col.key}`" :row="row">
                                    {{ row[col.key] }}
                                </slot>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <slot name="actions" :row="row" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
