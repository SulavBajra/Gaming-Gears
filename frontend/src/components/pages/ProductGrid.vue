<script setup lang="ts">
import type { HomeProduct } from '@/components/types'
import placeholder from '@/assets/placeholder.jpg'
import { Tag } from '@lucide/vue'

defineProps<{
  products?: HomeProduct[]
}>()

const formatPrice = (price: number) => {
  return `Rs. ${price.toLocaleString()}`
}
</script>

<template>
  <div class="grid-section">
    <div class="grid">
      <div v-for="product in products" :key="product.id" class="card">
        <!-- Image -->
        <div class="card-img">
          <img :src="product.thumbnail || placeholder" :alt="product.name" />
        </div>

        <!-- Content -->
        <div class="card-body">
          <p class="brand">
            {{ product.brand?.name }}
          </p>

          <h3 class="name">
            {{ product.name }}
          </h3>

          <div class="meta">
            <p class="category">
              {{ product.categories?.[0]?.name }}
            </p>

            <div class="tags">
              <Tag class="tag-icon" />
              <p>
                {{ product.tags?.join(', ') }}
              </p>
            </div>
          </div>

          <div class="bottom">
            <span class="price">
              {{ formatPrice(product.price) }}
            </span>

            <RouterLink :to="`/shop/${product.slug}`" class="btn"> View </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.grid-section {
  padding: 2.5rem 2rem;
}

.grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 2rem;
}

/* card */
.card {
  background: #2e4248;
  border: 1px solid #ffffff12;
  transition: 0.25s;
  display: flex;
  flex-direction: column;
  border-radius: 12px;
}

.card:hover {
  transform: translateY(-6px);
  background: #36545b;
  border-color: #ffffff25;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

/* image */
.card-img {
  aspect-ratio: 1;
  background: #1b2a2e;
  display: flex;
  align-items: center;
  justify-content: center;
  border-top-left-radius: 5%;
  border-top-right-radius: 5%;
  width: 100%;
}

.card-img img {
  width: 70%;
  object-fit: contain;
  transition: 0.3s;
}

.card:hover .card-img img {
  transform: scale(1.05);
}

/* body */
.card-body {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.brand {
  font-size: 0.7rem;
  letter-spacing: 0.1em;
  color: var(--ink);
  text-transform: uppercase;
  color: #c8a96e;
}

.name {
  font-size: 0.95rem;
  font-weight: 600;
  color: #f5f7f8;
}

.meta {
  display: flex;
  gap: 0.8rem;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
}

.category {
  font-size: 0.85rem;
  color: #aab6ba;
}

.tags {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: #aab6ba;
}

.tag-icon {
  size: 12px;
  stroke-width: 1px;
}

/* bottom */
.bottom {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 0.8rem;
}

.price {
  font-weight: 600;
  color: #f5f7f8;
}

.btn {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #c8a96e;
  text-decoration: none;
  transition: 0.2s;
}

.btn:hover {
  color: #ffffff;
}
</style>
