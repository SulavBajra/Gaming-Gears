<script setup lang="ts">
import Hero from '../components/pages/Hero.vue'
import HomeProduct from '../components/pages/HomeProduct.vue'
import BrandGrid from '../components/pages/BrandGrid.vue'
import type { Brand } from '@/components/types'
import { ref, onMounted } from 'vue'
import axiosClient from '@/axios'

const brands = ref<Brand[]>([])

onMounted(async () => {
  try {
    const res = await axiosClient.get('/api/brands')
    brands.value = res.data
  } finally {
  }
})
</script>
<template>
  <main>
    <Hero />
    <HomeProduct />
    <BrandGrid :brands="brands" />
  </main>
</template>
