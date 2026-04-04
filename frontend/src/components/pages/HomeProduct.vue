<script setup lang="ts">
import ProductGrid from './ProductGrid.vue'
import CategoryGrid from './CategoryGrid.vue'
import { ref, onMounted } from 'vue'
import type { HomeProduct } from '@/components/types'
import axiosClient from '@/axios'

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
    <div>
      <h1 class="product-heading">Recent Products</h1>
      <ProductGrid v-if="products.length" :products="products" />
    </div>
    <div class="route">
      <button class="view-all-btn">
        <RouterLink :to="'/shop'" class="view-all">Load More</RouterLink>
      </button>
    </div>
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
  padding-bottom: 1.5rem;
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

.route {
  text-align: center;
}

.view-all-btn {
  padding: 10px;
  background-color: var(--bg);
  border: 1px solid #ffffff25;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.view-all-btn:hover {
  transform: translateY(-6px);
  background: #36545b;
  border-color: #ffffff25;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.view-all {
  font-family: 'Oswald', sans-serif;
  font-size: 1.2rem;
  text-decoration: none;
  color: var(--ink);
}
</style>
