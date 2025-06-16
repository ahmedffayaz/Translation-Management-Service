<script setup>
import { ref } from 'vue'
import layoutFooter from "../Components/layoutFooter.vue"
import layoutHeader from "../Components/layoutHeader.vue"
import Toaster from "../Components/toaster.vue"
import { storeTranslations } from "../Apis/translation"

const form = ref({
    language_id: '',
    key: '',
    value: '',
     tags: ''
})

const messages = ref([])
const type = ref("")
const errors = ref({});
const languages = ref([
    { id: 1, code: 'en', name: 'English' },
    { id: 2, code: 'fr', name: 'French' },
    { id: 3, code: 'es', name: 'Spanish' },
    { id: 4, code: 'de', name: 'German' },
    { id: 5, code: 'ar', name: 'Arabic' },
])

const tagOptions = ref([
    { id: 1, name: 'web' },
    { id: 2, name: 'mobile' },
])
const submitForm = async () => {
    messages.value = ""
    type.value = ""
    const response = await storeTranslations(form.value)

    if (response.data.status == "true") {
        messages.value = response.data.message
        type.value = response.data.status
        form.value = {
            language_id: '',
            locale: '',
            key: '',
            value: '',
            context: ''
        }
    } else {
        type.value = response.status
        if (response.data.data) {
            messages.value = Object.values(response.data.data).flat()
        } else {
            type.value = response.data.status
            messages.value = Object.values(response.data.messages).flat()
        }
    }
}
</script>

<template>
    <layoutHeader />
    <div class="container mt-5">
        <h1 class="text-center">Add Translation</h1>
        <form @submit.prevent="submitForm">

            <!-- Tags Select First -->
            <select v-model="form.tags"  class="input mb-3">
                <option disabled value="">Select Tag</option>
                <option v-for="tag in tagOptions" :key="tag.id" :value="tag">
                    {{ tag.name }}
                </option>
            </select>

            <!-- Language Select -->
            <select v-model="form.language_id" class="input mb-3">
                <option disabled value="">Select Language</option>
                <option v-for="lang in languages" :key="lang.id" :value="lang.id">
                    {{ lang.name }} ({{ lang.code }})
                </option>
            </select>

            <!-- Translation Fields -->
            <input class="input" v-model="form.key" placeholder="Translation Key" />
            <input class="input" v-model="form.value" placeholder="Value" />

            <!-- Submit -->
            <button class="mt-2" type="submit">Save</button>
            <Toaster v-if="messages.length" :message="messages" :type="type" />
        </form>

    </div>
    <layoutFooter />
</template>
