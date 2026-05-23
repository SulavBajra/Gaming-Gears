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

    <div class="content">
      <div v-if="loading" class="spinner">
        <ProgressSpinner class="spinner-load" />
      </div>
      <div v-else-if="products.length === 0" class="empty-state">
        <div class="empty-icon-ring">
          <i class="ti ti-search-off" aria-hidden="true"></i>
        </div>
        <p class="empty-title">No products found</p>
        <p class="empty-sub">
          Try adjusting your filters or search term to find what you're looking for.
        </p>
        <div class="empty-actions">
          <button class="btn-clear" @click="onFilter({ category: null, sort: null, search: '' })">
            Clear filters
          </button>
          <RouterLink to="/shop" class="btn-browse">Browse all</RouterLink>
        </div>
      </div>

      <ProductGrid v-else :products="products" />
    </div>

    <div class="pagination-part">
      <Paginator
        class="pagination-part"
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
<style scoped>
.shop-view {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60vh;
}

.empty-state {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 4rem 2rem;
  text-align: center;
}

.empty-icon-ring {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  border: 1px solid var(--border);
  background: var(--surface);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--muted);
  font-size: 1.75rem;
}

.empty-title {
  font-family: 'Oswald', sans-serif;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--ink);
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.empty-sub {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  color: var(--muted);
  margin: 0;
  max-width: 280px;
  line-height: 1.6;
}

.empty-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.btn-clear {
  font-family: 'Oswald', sans-serif;
  font-size: 0.8rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 0.45rem 1rem;
  border-radius: 5px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--ink);
  cursor: pointer;
  transition:
    border-color 0.15s,
    color 0.15s;
}

.btn-clear:hover {
  border-color: var(--accent);
  color: var(--accent);
}

.btn-browse {
  font-family: 'Oswald', sans-serif;
  font-size: 0.8rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 0.45rem 1rem;
  border-radius: 5px;
  background: var(--accent);
  color: var(--bg);
  text-decoration: none;
  transition: opacity 0.15s;
}

.btn-browse:hover {
  opacity: 0.85;
}

.pagination-part {
  display: flex;
  justify-content: center;
}
</style>
