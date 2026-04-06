<script setup lang="ts">
import axiosClient from '@/axios'
import { onMounted, ref } from 'vue'

const items = ref([])

onMounted(async () => {
  const response = await axiosClient.get('/api/wishlist')
})
</script>
<template>
  <div>
    <ul></ul>
  </div>
</template>
