<template>
    <div class="bg-white rounded-lg shadow-md border border-gray-300 p-8 w-[900px] mx-auto">
        <h2 class="text-3xl font-bold mb-8">{{ title() }}</h2>

        <div class="grid grid-cols-2 gap-10">
            <div>
            <h3 class="text-xl font-semibold mb-2">
                {{ detailsTitle() }}
            </h3>
            <hr class="border-[#D9D9D9] mb-4" />
            <div v-if="isPeopleType() && !loading">
                <p>Birth Year: {{ item.birth_year }}</p>
                <p>Gender: {{ item.gender }}</p>
                <p>Eye Color: {{ item.eye_color }}</p>
                <p>Hair Color: {{ item.hair_color }}</p>
                <p>Height: {{ item.height }}</p>
                <p>Mass: {{ item.mass }}</p>
            </div>

            <div v-else-if="!loading">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ item.opening_crawl }}
                </p>
            </div>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-2">
                    {{relatedLinksTitle()}}
                </h3>

                <hr class="border-[#D9D9D9] mb-4" />

                <ul class="space-y-1" v-if="!loading">
                    <li v-for="rel in relatedList" :key="rel.id">
                        <a
                            class="text-blue-600 hover:underline cursor-pointer"
                            @click="selectRelated(rel)"
                        >
                            {{ rel.title || rel.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-10">
            <button
                class="px-10 py-3 bg-green-600 text-white font-semibold rounded-full cursor-pointer hover:bg-green-700"
                @click="emit('back')"
            >
                BACK TO SEARCH
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import apiClient from "../../services/swApi.js"

const loading = ref(false)
const item = ref({})
const relatedList = ref([])
const emit = defineEmits(['back', 'showDetails'])
const props = defineProps({
    type: String,
    id: [String, Number]
})

async function getDetail() {
    loading.value = true
    try {
        console.log("ID", props.id)
        const response = await apiClient.getDetails(props.type, props.id)
        item.value = response.data;
        relatedList.value = isPeopleType()
            ? item.value.films
            : item.value.characters
    } catch (error) {
        console.error('Error during search:', error)
    } finally {
        loading.value = false
    }
}

function isPeopleType() {
    return props.type === 'people'
}

function title() {
    if(loading.value) {
        return "Loading..."
    }
    return isPeopleType() ? item.value.name : item.value.title;
}

function detailsTitle() {
    return isPeopleType() ? "Details" : "Opening Crawl";
}

function relatedLinksTitle() {
    return isPeopleType() ? "Movies" : "Characters";
}

function selectRelated(rel) {
    emit("showDetails", {
        item: rel,
        type: isPeopleType() ? "films" : "people",
    })
}

onMounted(getDetail)
watch(() => [props.type, props.id], getDetail)
</script>
