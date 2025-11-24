<template>
    <div>
        <AppBar />

        <div class="max-w-6xl mx-auto px-6 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Left Card: Search -->
                <SearchCard @results="updateResults" v-if="!isDetailsView()" @isLoading="loadingUpdate"/>

                <!-- Right Card: Results -->
                <ResultsCard :loading="loading" :results="results" :type="typeRef" v-if="!isDetailsView()" @showDetails="showDetails"/>
            </div>
            <div class="flex justify-center w-full mt-10">
                <DetailsCard :type="typeRef" :id="idRef" v-if="isDetailsView()" @show-details="showDetails" @back="backToSearch"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import AppBar from './components/AppBar.vue'
import SearchCard from './components/SearchCard.vue'
import ResultsCard from './components/ResultsCard.vue'
import DetailsCard from "./components/DetailsCard.vue";

const results = ref([])
const currentView = ref('search')
const typeRef = ref('')
const idRef = ref(null)
const loading = ref(false)

function isDetailsView() {
    return currentView.value === 'details'
}
function updateResults(newResults) {
    results.value = newResults.results
    typeRef.value = newResults.type
}

function showDetails({ item, type }) {
    currentView.value = 'details'
    typeRef.value = type
    idRef.value = item.id
}

function backToSearch() {
    currentView.value = 'search'
    results.value = []
    typeRef.value = ''
    idRef.value = null
}

function loadingUpdate(isLoading) {
    loading.value = isLoading
}
</script>

<style>
/* optional global styles */
</style>
