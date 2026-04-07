<script setup lang="ts">
import { ref } from 'vue'
import Toolbar from 'primevue/toolbar'
import Select from 'primevue/select'
import IconField from 'primevue/iconfield'
import InputText from 'primevue/inputtext'
import { useDebounceFn } from '@vueuse/core'
import type { Brand } from '@/components/types'

const selectedCategory = ref<string | null>(null)
const selectedSort = ref<string | null>(null)
const selectedBrand = ref<string | null>(null)
const searchQuery = ref<string>('')

defineProps<{
  brands: Brand[]
}>()

const categories = ref([
  { label: 'All Categories', slug: '' },
  { label: 'Keyboards', slug: 'keyboards' },
  { label: 'Mice', slug: 'mice' },
  { label: 'HeadSets', slug: 'headsets' },
])

const sortOptions = ref([
  { label: 'Default', value: '' },
  { label: 'Price: Low to High', value: 'price_asc' },
  { label: 'Price: High to Low', value: 'price_desc' },
  { label: 'A → Z', value: 'name_asc' },
  { label: 'Z → A', value: 'name_desc' },
])

const emit = defineEmits<{
  (
    e: 'filter',
    payload: { category: string | null; sort: string | null; brand: string | null; search: string },
  ): void
}>()

function emitFilter() {
  emit('filter', {
    category: selectedCategory.value,
    sort: selectedSort.value,
    search: searchQuery.value,
    brand: selectedBrand.value,
  })
}

const debouncedSearch = useDebounceFn(emitFilter, 400)
</script>
<template>
  <div class="card">
    <Toolbar class="filter-bar">
      <template #start>
        <div class="drop-down-filter">
          <div class="category-filter">
            <Select
              v-model="selectedCategory"
              :options="categories"
              optionLabel="label"
              optionValue="slug"
              placeholder="Select a Category"
              class="category-select"
              @update:modelValue="emitFilter"
            />
          </div>
          <div class="brand-filter">
            <Select
              v-model="selectedBrand"
              :options="brands"
              optionLabel="name"
              optionValue="slug"
              placeholder="Select a Brand"
              class="category-select"
              @update:modelValue="emitFilter"
            />
          </div>
        </div>
      </template>
      <template class="center-filter" #center>
        <div class="price-filter">
          <Select
            v-model="selectedSort"
            :options="sortOptions"
            optionLabel="label"
            optionValue="value"
            placeholder="Select a sort"
            class="price-select"
            @update:modelValue="emitFilter"
          />
        </div>
      </template>
      <template #end
        ><IconField>
          <InputText
            v-model="searchQuery"
            placeholder="Search"
            class="search-filter"
            @update:modelValue="debouncedSearch"
          />
        </IconField>
      </template>
    </Toolbar>
  </div>
</template>

<style scoped>
.card {
  padding: 10px;
  display: flex;
  justify-content: center;
}

.filter-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 10px;
  border-radius: 10px;
  background-color: var(--bg);
  border: none;
}

.drop-down-filter {
  display: flex;
  gap: 20px;
}

.category-filter {
  color: var(--accent);
  display: flex;
  gap: 5px;
  align-items: center;
}

.brand-filter {
  color: var(--accent);
  display: flex;
  gap: 5px;
  align-items: center;
}

.category-select {
  border-radius: 10px;
  background-color: var(--bg);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
}

.price-filter {
  color: var(--accent);
  display: flex;
  gap: 5px;
  align-items: center;
}

.price-select {
  border-radius: 10px;
  background-color: var(--bg);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
}

.search-filter {
  border-radius: 10px;
  background-color: var(--bg);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
}

.alphabetical-filter {
  margin-left: 15px;
  color: var(--accent);
  display: flex;
  gap: 5px;
  align-items: center;
}

.alphabetical-select {
  border-radius: 10px;
  background-color: var(--bg);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
}
</style>
