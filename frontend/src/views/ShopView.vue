<script setup lang="ts">
import ProductGrid from '@/components/pages/ProductGrid.vue'
import { ref, onMounted } from 'vue'
import axiosClient from '@/axios'
import type { HomeProduct } from '@/components/types'
import ProgressSpinner from 'primevue/progressspinner'

const products = ref<HomeProduct[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await axiosClient.get('/api/shops')
    products.value = res.data.data
  } finally {
    loading.value = false
  }
})
</script>
<template>
  <section class="shop-view">
    <div v-if="loading" class="spinner">
      <ProgressSpinner />
    </div>
    <ProductGrid v-if="products.length" :products="products" :loading="loading" />
  </section>
</template>

<style scoped>
.shop-view {
  height: 100%;
}

.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50%;
}
</style>
