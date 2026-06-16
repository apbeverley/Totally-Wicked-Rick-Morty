<template>
    <div class="container mx-auto p-4 md:p-8 max-w-4xl pt-12">
        <div class="mb-8">
            <Link href="/" class="text-blue-600 hover:text-blue-800 flex items-center font-medium transition-colors hover:-translate-x-1 transform duration-200 w-max">
                <span class="mr-2">&larr;</span> Back to Roster
            </Link>
        </div>

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-xl mb-8 shadow-sm text-center">
            <h3 class="text-lg font-bold mb-2">Error Retrieving Data</h3>
            <p>{{ error }}</p>
        </div>

        <div v-else-if="character" class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="md:flex">
                <div class="md:shrink-0 md:w-5/12 bg-gray-50 flex items-center justify-center p-8 border-b md:border-b-0 md:border-r border-gray-100">
                    <img :src="character.image" :alt="character.name" class="h-64 w-64 md:h-80 md:w-80 object-cover rounded-full shadow-lg ring-4 ring-white border border-gray-200" />
                </div>
                <div class="p-8 md:p-10 md:w-7/12 flex flex-col justify-center">
                    <div class="uppercase tracking-wide text-xs text-blue-600 font-bold mb-1">Character Profile</div>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 tracking-tight">{{ character.name }}</h1>

                    <div class="space-y-4 mb-10 bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <div class="flex border-b border-gray-200 pb-3">
                            <span class="font-semibold text-gray-500 w-24">Status</span>
                            <span class="text-gray-900 font-medium flex items-center">
                <span v-if="character.status.toLowerCase() === 'alive'" class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                <span v-else-if="character.status.toLowerCase() === 'dead'" class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                <span v-else class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>
                {{ character.status }}
              </span>
                        </div>

                        <div class="flex border-b border-gray-200 pb-3">
                            <span class="font-semibold text-gray-500 w-24">Species</span>
                            <span class="text-gray-900 font-medium">{{ character.species }}</span>
                        </div>

                        <div class="flex border-b border-gray-200 pb-3">
                            <span class="font-semibold text-gray-500 w-24">Gender</span>
                            <span class="text-gray-900 font-medium">{{ character.gender }}</span>
                        </div>

                        <div class="flex pt-1">
                            <span class="font-semibold text-gray-500 w-24">Origin</span>
                            <span class="text-gray-900 font-medium">{{ character.origin }}</span>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                            Appearances
                        </h2>
                        <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto pr-2 pb-2 custom-scrollbar">
              <span
                  v-for="ep in character.episodes"
                  :key="ep"
                  class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100"
              >
                Episode {{ ep }}
              </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Character } from '@/types';

defineProps({
    character: {
        type: Object as () => Character,
        default: null
    },
    error: {
        type: String,
        default: null
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
