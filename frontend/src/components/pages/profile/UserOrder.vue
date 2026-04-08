<script setup lang="ts">
import axiosClient from '@/axios'
import { ref } from 'vue'

const orders = ref([])

const fetchOrder = async () => {
  const { data } = await axiosClient.get('/api/orders')
  orders.value = data
}
fetchOrder()
</script>

<template>
  <div>
    {{ orders }}
  </div>
</template>
