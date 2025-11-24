<template>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
        <h2 class="text-xl font-semibold mb-4">What are you searching for?</h2>

        <!-- Type selection -->
        <div class="flex items-center gap-6 mb-4">
            <label class="flex items-center gap-2">
                <input type="radio" value="people" v-model="type" />
                <span>People</span>
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" value="films" v-model="type" />
                <span>Movies</span>
            </label>
        </div>

        <input
            type="text"
            v-model="searchQuery"
            placeholder="Search..."
            class="w-full border rounded-lg p-2 mb-6"
        />

        <button class="w-full py-3 text-white font-semibold rounded-full"
                :class="searchButtomClass()"
                :disabled="searchDisabled()"
                @click="searchCall">
            {{ isLoading ? 'Searching...' : 'Search' }}
        </button>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import apiClient from "../../services/swApi.js";
const searchQuery = ref('')
const type = ref('people')
const isLoading = ref(false)
const emit = defineEmits(['results'])

async function searchCall() {
    isLoading.value = true

    try {
        const response = await apiClient.getList(type.value, searchQuery.value)
        emit('results', {
            type: type.value,
            results: response.data
        })
    } catch (error) {
        console.error('Error during search:', error)
        emit('results', {
            type: type.value,
            results: []
        })
    } finally {
        isLoading.value = false
    }
}

function searchButtomClass() {
    return searchDisabled() ? 'bg-gray-400' : 'bg-green-600 hover:bg-green-700 cursor-pointer'
}

function searchDisabled() {
    return searchQuery.value.trim() === '' || isLoading.value
}

watch(() => isLoading.value, (newVal) => {
    emit('isLoading', newVal)
})
</script>
