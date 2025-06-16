<script setup>
import { reactive, ref } from 'vue'
import { register } from "../Apis/auth.js"
import { useRouter } from 'vue-router'
import toaster from "../Components/toaster.vue"

const router = useRouter();
const credentials = reactive({
    name: '',
    email: '',
    password: ''
})
const messages = ref([])
const type = ref("");
const errors = ref({});

const registerUser = async () => {
    messages.value = []
    type.value = ""

    const response = await register(credentials)
    
    if (response.status === "true") {
        localStorage.setItem('token', response.data)
        router.push('/home-page')
    } else {
        if (response.data) {
            type.value = response.status
            messages.value = Object.values(response.data).flat()
        } else {
            type.value = 'error'
            messages.value = [response.message || "Something went wrong"]
        }
    }
}

</script>

<template>
    <div class="body">
        <div class="register">
            <div class="form-container sign-in-container">
                <form @submit.prevent="registerUser">
                    <h1 class="mt-4">Sign up</h1>
                    <input class="input" v-model="credentials.name" placeholder="Name" />
                    <input class="input" v-model="credentials.email" placeholder="Email" />
                    <input class="input" type="password" v-model="credentials.password" placeholder="Password" />
                    <button class="my-4" type="submit">Sign up</button>
                    <h5><b>OR</b></h5>
                    <router-link :to="{ name: 'login' }">Sign In</router-link>
                </form>
            </div>
        </div>
    </div>
   <toaster v-if="messages.length" :message="messages" :type="type" />
</template>
