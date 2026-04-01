<script setup lang="ts">
import ProductGrid from './ProductGrid.vue'
import CategoryGrid from './CategoryGrid.vue'
import { ref, onMounted } from 'vue'
import type { Category, HomeProduct } from '@/components/types'
import axiosClient from '@/axios'

const categories = ref<Category[]>([])
const products = ref<HomeProduct[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await axiosClient.get('/api/home')
    products.value = res.data.data
  } finally {
    loading.value = false
  }
})
</script>
<template>
  <section class="categories">
    <h1 class="product-heading">Our Categories</h1>
    <CategoryGrid />
  </section>
  <section class="recent-products">
    <h1 class="product-heading">Recent Products</h1>
    <ProductGrid v-if="products.length" :products="products" />
  </section>
</template>
<style scoped>
.categories {
  background-color: var(--bg);
  border-top: dashed #ffff;
  padding-top: 1.5rem;
}

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
