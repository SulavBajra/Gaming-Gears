<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import ProductGrid from '@/components/pages/ProductGrid.vue'
import type { HomeProduct } from '@/components/types'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
})

const route = useRoute()

const product = ref<HomeProduct | null>(null)
const similarProducts = ref<HomeProduct[]>([])
const loading = ref(true)

const fetchProduct = async (slug: string) => {
  loading.value = true

  try {
    const res = await api.get(`/api/shop/${slug}`)
    product.value = res.data.data

    if (product.value?.categories?.length) {
      const catSlug = product.value.categories[0]?.slug
      const sim = await api.get(`/api/shop/category/${catSlug}`)
      similarProducts.value = sim.data.data
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProduct(route.params.slug as string)
})

watch(
  () => route.params.slug,
  (slug) => {
    if (slug) fetchProduct(slug as string)
  },
)
</script>

<template>
  <div v-if="loading">Loading...</div>

  <div v-else-if="product">
    <h1>{{ product.name }}</h1>

    <img :src="product.thumbnail" />

    <p>{{ product.description }}</p>

    <p>Rs. {{ product.price }}</p>
  </div>

  <div v-else>Product not found</div>

  <section v-if="similarProducts.length" class="similar-products">
    <h2>Similar Products</h2>
    <ProductGrid :products="similarProducts" />
  </section>
</template>
