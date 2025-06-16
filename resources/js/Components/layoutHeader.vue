<script setup>
import { logout } from "../Apis/auth.js"
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const type = ref("")
const messages = ref([])

const handleLogout = async () => {
  localStorage.removeItem('token')
  router.push({ name: 'login' }) 
  const response = await logout()
  if (response.status === "true") {
    type.value = response.status || 'error'
    messages.value = [response.message || "Something went wrong"]
  }
}
</script>

<template>
  <div class="nav">
    <div class="container">
      <div class="brand">Translation Management Service</div>
      <label for="menu-toggle" class="menu-icon">&#9776;</label>
      <input type="checkbox" id="menu-toggle" />
      <ul class="nav-links">
        <li><router-link :to="{ name: 'home' }">Home</router-link></li>
        <li><router-link :to="{ name: 'AddTranslation' }">Add Translation</router-link></li>
        <li><a href="#" @click.prevent="handleLogout" class="text-danger"><b>Logout</b></a></li>
      </ul>
    </div>
  </div>
</template>
