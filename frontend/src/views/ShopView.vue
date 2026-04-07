<script setup lang="ts">
import ProductGrid from '@/components/pages/ProductGrid.vue'
import FilterBar from '@/components/pages/FilterBar.vue'
import { ref, onMounted } from 'vue'
import axiosClient from '@/axios'
import type { HomeProduct, ShopMeta, Brand } from '@/components/types'
import Paginator from 'primevue/paginator'
import ProgressSpinner from 'primevue/progressspinner'
import { useRoute } from 'vue-router'

const products = ref<HomeProduct[]>([])
const meta = ref<ShopMeta | null>(null)
const brands = ref<Brand[]>([])
const loading = ref(true)
const first = ref(0)
const perPage = 12
const selectedCategory = ref<string | null>(null)
const selectedBrand = ref<string | null>(null)
const selectedSort = ref<string | null>(null)
const selectedSearch = ref<string>('')
const route = useRoute()

const fetchProducts = async (
  page = 1,
  category?: string,
  sort?: string,
  search?: string,
  brand?: string,
) => {
  loading.value = true
  try {
    const res = await axiosClient.get('/api/shops', {
      params: { page, category, sort, search, brand, per_page: perPage },
    })
    products.value = res.data.data
    meta.value = res.data.meta
    first.value = (res.data.meta.current_page - 1) * res.data.meta.per_page
  } finally {
    loading.value = false
  }
}

const fetchBrand = async () => {
  loading.value = true
  try {
    const res = await axiosClient.get(`/api/brands`)
    brands.value = res.data
  } finally {
    loading.value = false
  }
}

const onFilter = (payload: {
  category: string | null
  sort: string | null
  brand: string | null
  search: string
}) => {
  selectedCategory.value = payload.category
  selectedSort.value = payload.sort
  selectedSearch.value = payload.search
  selectedBrand.value = payload.brand
  fetchProducts(
    1,
    selectedCategory.value || undefined,
    selectedSort.value || undefined,
    payload.search || undefined,
    selectedBrand.value || undefined,
  )
}

const onPageChange = (event: { page: number }) => {
  fetchProducts(
    event.page + 1,
    selectedCategory.value || undefined,
    selectedSort.value || undefined,
    selectedSearch.value || undefined,
    selectedBrand.value || undefined,
  )
}
onMounted(() => {
  const categoryFromQuery = route.query.category as string | undefined
  if (categoryFromQuery) selectedCategory.value = categoryFromQuery
  fetchProducts(1, selectedCategory.value || undefined)
  fetchBrand()
})
</script>
<template>
  <section class="shop-view">
    <FilterBar @filter="onFilter" :brands="brands" />

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
