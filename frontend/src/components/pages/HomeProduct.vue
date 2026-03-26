<script setup lang="ts">
import ProductGrid from './ProductGrid.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import type { HomeProduct } from '@/components/types'

const products = ref<HomeProduct[]>([])
const loading = ref(true)
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
})

onMounted(async () => {
  try {
    const res = await api.get('/api/home')
    products.value = res.data.data
  } finally {
    loading.value = false
  }
})
</script>
<template>
  <section class="recent-products">
    <h1 class="product-heading">Recent Products</h1>
    <ProductGrid v-if="products.length" :products="products" />
  </section>
</template>
<style scoped>
.recent-products {
  background-color: var(--bg);
  border-top: dashed #ffff;
  padding-top: 1.5rem;
}
.product-heading {
  font-family: 'Oswald', sans-serif;
  font-size: clamp(3rem, 5.5vw, 2rem);
  font-weight: 700;
  line-height: 0.92;
  letter-spacing: -0.02em;
  color: var(--ink);
  text-align: center;
}
</style>
