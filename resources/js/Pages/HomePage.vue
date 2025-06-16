<script setup>
import { ref, watch, onBeforeMount } from 'vue'
import layoutFooter from "../Components/layoutFooter.vue"
import layoutHeader from "../Components/layoutHeader.vue"
import { getTranslations } from "../Apis/translation"

const translation = ref([])
const currentPage = ref(1)
const totalPages = ref(1)
const filters = ref({
    search: ''
})

const fetchTranslations = async () => {
    const result = await getTranslations(filters.value, currentPage.value)
    translation.value = result.data.translations
    totalPages.value = result.data.last_page || 1
}

const changePage = (newPage) => {

    if (newPage >= 1 && newPage <= totalPages.value) {
        currentPage.value = newPage
        fetchTranslations()
    }
}

watch(() => filters.value.search, () => {
    currentPage.value = 1
    fetchTranslations()
})

onBeforeMount(fetchTranslations)
</script>


<template>
    <layoutHeader />
    <div class="p-5" v-if="translation">
        <h2 class="text-xl font-bold">Translations</h2>
        <div class="flex items-center justify-between flex-wrap mb-4">
            <div class="flex-1 mr-4">
                <input v-model="filters.search" @input="fetchTranslations"
                    placeholder="Search by key, value, tag or language"
                    class="border border-gray-300 rounded px-3 py-1 w-full max-w-xs w-50 my-3" />
            </div>

            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Prev
                </button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Next
                </button>
            </div>
        </div>


        <table class="table-auto w-full border-collapse w-100">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Key</th>
                    <th class="border px-4 py-2 text-left">Value</th>
                    <th class="border px-4 py-2 text-left">Language</th>
                    <th class="border px-4 py-2 text-left">Tags</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in translation" :key="item.id">
                    <td class="border px-4 py-2">{{ item.key }}</td>
                    <td class="border px-4 py-2">{{ item.value }}</td>
                    <td class="border px-4 py-2">{{ item.language.code }}</td>
                    <td class="border px-4 py-2">
                        <span v-if="item.tags && item.tags.length">
                            {{ item.tags.join(', ') }}
                        </span>
                        <span v-else class="text-gray-400 italic">No tags</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else>
        <h5>No Content Found</h5>
    </div>
    <layoutFooter />
</template>
