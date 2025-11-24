<template>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300 min-h-[400px]">
        <h2 class="text-xl font-semibold mb-4">Results</h2>
        <hr />

        <div class="flex flex-col h-full text-gray-700">
            <div v-if="props.results.length === 0">
                <p>No results to display.</p>
            </div>
            <div v-else-if="props.loading">
                <p>Searching</p>
            </div>
            <div v-else>
                <ul class="w-full list-none divide-y divide-grey-200">
                    <li class="flex justify-between items-center py-3 w-full" v-for="(item, index) in props.results" :key="index">
                            <div class="text-xl font-semibold">{{ item.name || item.title }}</div>
                            <div>
                                <button class="px-6 py-2 bg-green-600 text-white font-bold rounded-full hover:bg-green-700 cursor-pointer"
                                        @click="$emit('showDetails', { item, type:props.type })"
                                >SEE DETAILS</button>
                            </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    results: Array,
    type: String,
    loading: Boolean
})

</script>
