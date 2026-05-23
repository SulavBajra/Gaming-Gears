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

      <!-- Skeleton loader -->
      <div v-if="loading" class="skeleton-grid">
        <div v-for="n in 8" :key="n" class="skeleton-card">
          <div class="skeleton-img shimmer" />
          <div class="skeleton-body">
            <div class="skeleton-line shimmer" style="width: 65%" />
            <div class="skeleton-line shimmer" style="width: 40%" />
            <div class="skeleton-line shimmer" style="width: 50%; margin-top: 0.75rem" />
          </div>
        </div>
      </div>

      <ProductGrid v-else-if="products.length" :products="products" />
    </div>

    <div class="route">
      <button class="view-all-btn">
        <RouterLink :to="'/shop'" class="view-all">Load More</RouterLink>
      </button>
    </div>
  </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Iosevka+Charon:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Oswald:wght@200..700&display=swap');

.categories {
  background-color: var(--bg);
  border-top: dashed #ffff;
  padding-top: 1.5rem;
  font-family: 'Iosevka Charon', monospace;
}

.recent-products {
  background-color: var(--bg);
  border-top: dashed #ffff;
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
  font-family: 'Iosevka Charon', monospace;
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

/* ── Skeleton ── */
.skeleton-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.25rem;
  padding: 1.5rem;
}

.skeleton-card {
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid #ffffff10;
  background-color: #ffffff08;
}

.skeleton-img {
  width: 100%;
  aspect-ratio: 1 / 1;
  border-radius: 0;
}

.skeleton-body {
  padding: 0.85rem;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.skeleton-line {
  height: 12px;
  border-radius: 4px;
}

/* Shimmer animation */
.shimmer {
  background: linear-gradient(
    90deg,
    #ffffff08 0%,
    #ffffff18 40%,
    #ffffff08 80%
  );
  background-size: 200% 100%;
  animation: shimmer 1.4s ease-in-out infinite;
}

@keyframes shimmer {
  0%   { background-position: 200% center; }
  100% { background-position: -200% center; }
}

/* ── Existing styles ── */
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
