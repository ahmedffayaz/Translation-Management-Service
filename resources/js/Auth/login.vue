<script setup>
import { login } from "../Apis/auth.js"
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import Toaster from "../Components/toaster.vue"

const router = useRouter()

const credentials = reactive({
    email: '',
    password: ''
})

const messages = ref([])
const type = ref("")
const errors = ref({})

const handleLogin = async () => {
    messages.value = []
    type.value = ""

    const response = await login(credentials)
    
    if (response.data.status == true) {
        
        type.value = response.status
        localStorage.setItem('token', response.data.token)
        router.push('/home-page')
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
>

<template>
  <div class="body">
    <div class="login">
      <div class="form-container sign-in-container">
        <form @submit.prevent="handleLogin">
          <h1 class="my-4">Sign in</h1>
          <input class="input" type="email" v-model="credentials.email" placeholder="Email" />
          <input class="input" type="password" v-model="credentials.password" placeholder="Password" />
          <button class="my-4" type="submit">Sign In</button>
          <h5><b>OR</b></h5>
          <router-link :to="{ name: 'register' }">Sign Up</router-link>
        </form>
      </div>
    </div>
  </div>
  <Toaster v-if="messages.length" :message="messages" :type="type" />
</template>
