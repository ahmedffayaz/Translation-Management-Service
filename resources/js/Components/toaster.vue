<template>
  <div v-if="visible" :class="['toaster', statusClass]">
    <ul v-if="Array.isArray(message)">
      <li v-for="(msg, index) in message" :key="index">{{ msg }}</li>
    </ul>
    <p v-else>{{ message }}</p>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'

const props = defineProps({
  type: { type: String, default: 'success' },
  message: { type: [String, Array], required: true }
})

const visible = ref(true)

onMounted(() => {
  setTimeout(() => {
    visible.value = false
  }, 4000)
})

const statusClass = computed(() => {
  return props.type === 'true' ? 'success' : 'error'
})
</script>
