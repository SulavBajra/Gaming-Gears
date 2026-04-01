<script setup lang="ts">
import ProductGrid from '@/components/pages/ProductGrid.vue'
import FilterBar from '@/components/pages/FilterBar.vue'
import { ref, onMounted } from 'vue'
import axiosClient from '@/axios'
import type { HomeProduct, ShopMeta } from '@/components/types'
import Paginator from 'primevue/paginator'
import ProgressSpinner from 'primevue/progressspinner'

const products = ref<HomeProduct[]>([])
const meta = ref<ShopMeta | null>(null)
const loading = ref(true)
const first = ref(0)
const perPage = 12

const fetchProducts = async (page = 1) => {
  loading.value = true
  try {
    const res = await axiosClient.get('/api/shops', { params: { page } })
    products.value = res.data.data
    meta.value = res.data.meta
    first.value = (res.data.meta.current_page - 1) * res.data.meta.per_page
  } finally {
    loading.value = false
  }
}

const onPageChange = (event: { page: number; first: number }) => {
  fetchProducts(event.page + 1)
}
onMounted(() => fetchProducts())
</script>
<template>
  <section class="shop-view">
    <FilterBar />

    <div v-if="loading" class="spinner">
      <ProgressSpinner />
    </div>

    <ProductGrid v-else :products="products" />

    <div class="pagination-part">
      <Paginator
        :class="$style.paginator"
        v-if="meta && meta.last_page > 1"
        v-model:first="first"
        :rows="perPage"
        :totalRecords="meta.total"
        template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        @page="onPageChange"
      />
    </div>
  </section>
</template>
<style scoped module>
.shop-view {
  height: 100%;
}

.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50%;
}

.paginator {
  display: flex;
  justify-content: center;
  color: red;
}
</style>
