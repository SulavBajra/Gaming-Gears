<script setup lang="ts">
import ProductGrid from '@/components/pages/ProductGrid.vue'
import FilterBar from '@/components/pages/FilterBar.vue'
import { ref, onMounted } from 'vue'
import axiosClient from '@/axios'
import type { HomeProduct, ShopMeta } from '@/components/types'
import Paginator from 'primevue/paginator'
import ProgressSpinner from 'primevue/progressspinner'
import { useRoute } from 'vue-router'

const products = ref<HomeProduct[]>([])
const meta = ref<ShopMeta | null>(null)
const loading = ref(true)
const first = ref(0)
const perPage = 12
const selectedCategory = ref<string | null>(null)
const selectedSort = ref<string | null>(null)
const selectedSearch = ref<string>('')
const route = useRoute()

const fetchProducts = async (page = 1, category?: string, sort?: string, search?: string) => {
  loading.value = true
  try {
    const res = await axiosClient.get('/api/shops', {
      params: { page, category, sort, search, per_page: perPage },
    })
    products.value = res.data.data
    meta.value = res.data.meta
    first.value = (res.data.meta.current_page - 1) * res.data.meta.per_page
  } finally {
    loading.value = false
  }
}

const onFilter = (payload: { category: string | null; sort: string | null; search: string }) => {
  selectedCategory.value = payload.category
  selectedSort.value = payload.sort
  selectedSearch.value = payload.search
  fetchProducts(
    1,
    selectedCategory.value || undefined,
    selectedSort.value || undefined,
    payload.search || undefined,
  )
}

const onPageChange = (event: { page: number }) => {
  fetchProducts(
    event.page + 1,
    selectedCategory.value || undefined,
    selectedSort.value || undefined,
    selectedSearch.value || undefined,
  )
}
onMounted(() => {
  const categoryFromQuery = route.query.category as string | undefined
  if (categoryFromQuery) selectedCategory.value = categoryFromQuery
  fetchProducts(1, selectedCategory.value || undefined)
})
</script>
<template>
  <section class="shop-view">
    <FilterBar @filter="onFilter" />

    <div v-if="loading" :class="$style.spinner">
      <ProgressSpinner class="spinner-load" />
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
<style module>
.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60vh;
}

.paginator {
  display: flex;
  justify-content: center;
}
</style>
