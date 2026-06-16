<template>
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-8 shadow-sm">
            {{ error }}
        </div>

        <!-- Filters Form -->
        <form @submit.prevent="applyFilters" class="mb-8 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="e.g. Rick"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Any</option>
                        <option value="alive">Alive</option>
                        <option value="dead">Dead</option>
                        <option value="unknown">Unknown</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Species</label>
                    <input
                        v-model="form.species"
                        type="text"
                        placeholder="e.g. Human"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select v-model="form.gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Any</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="genderless">Genderless</option>
                        <option value="unknown">Unknown</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end items-center border-t border-gray-100 pt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- Character Grid -->
        <div v-if="characters && characters.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <Link
                v-for="char in characters"
                :key="char.id"
                :href="`/${char.id}`"
                class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:border-gray-300 transition-all duration-300"
            >
                <div class="relative overflow-hidden">
                    <img :src="char.image" :alt="char.name" class="w-full h-56 object-cover object-center group-hover:scale-105 transition-transform duration-500" />
                </div>
                <div class="p-5">
                    <h2 class="text-xl font-bold text-gray-900 mb-3 truncate group-hover:text-blue-600 transition-colors" :title="char.name">{{ char.name }}</h2>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600 flex items-center">
                            <span class="w-16 text-gray-400 font-medium">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800" :class="{ 'bg-green-100 text-green-800': char.status.toLowerCase() === 'alive', 'bg-red-100 text-red-800': char.status.toLowerCase() === 'dead' }">{{ char.status }}</span>
                        </p>
                        <p class="text-sm text-gray-600 flex items-center">
                            <span class="w-16 text-gray-400 font-medium">Species</span>
                            <span class="text-gray-900">{{ char.species }}</span>
                        </p>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Empty State -->
        <div v-else-if="!error" class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">No characters found</h3>
            <p class="text-gray-500">We couldn't find any characters matching your current filters.</p>
        </div>

        <!-- Pagination controls -->
        <div v-if="pagination && (pagination.prev || pagination.next)" class="mt-12 flex justify-between items-center py-4 border-t border-gray-200">
            <button
                @click="goToPage(pagination.prev)"
                :disabled="!pagination.prev"
                class="inline-flex items-center px-6 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
                &larr; Previous Page
            </button>

            <button
                @click="goToPage(pagination.next)"
                :disabled="!pagination.next"
                class="inline-flex items-center px-6 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
                Next Page &rarr;
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Character, Pagination } from '@/types';

const props = defineProps({
    characters: {
        type: Array as () => Character[],
        default: () => []
    },
    pagination: {
        type: Object as () => Pagination,
        default: () => ({})
    },
    filters: {
        type: Object as () => Record<string, string>,
        default: () => ({})
    },
    error: {
        type: String,
        default: null
    }
});

const form = ref({
    name: props.filters?.name || '',
    status: props.filters?.status || '',
    species: props.filters?.species || '',
    gender: props.filters?.gender || ''
});

const applyFilters = () => {
    const activeFilters = Object.fromEntries(
        Object.entries(form.value).filter(([_, v]) => v !== '' && v !== null)
    );

    router.get('/', activeFilters, { preserveState: true, replace: true, preserveScroll: true });
};

const goToPage = (url: string | null) => {
    if (!url) {
        return;
    }

    const urlObj = new URL(url);
    const page = urlObj.searchParams.get('page');

    const currentFilters = Object.fromEntries(
        Object.entries(form.value).filter(([_, v]) => v !== '' && v !== null)
    );

    if (page) {
        currentFilters.page = page;
    }

    router.get('/', currentFilters, { preserveState: true, replace: true, preserveScroll: true });
};
</script>
