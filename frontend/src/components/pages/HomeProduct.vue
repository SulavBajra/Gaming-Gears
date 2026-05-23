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
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  padding: 2rem;
}

.skeleton-card {
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.06);
  background: linear-gradient(145deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.015));
  backdrop-filter: blur(12px);
  box-shadow:
    0 8px 28px rgba(0, 0, 0, 0.18),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  transition: transform 0.3s ease;
}

.skeleton-card:hover {
  transform: translateY(-6px);
}

.skeleton-img {
  width: 100%;
  aspect-ratio: 1 / 1;
  background: rgba(255, 255, 255, 0.03);
}

.skeleton-body {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
}

.skeleton-line {
  height: 12px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.05);
}

.skeleton-line:nth-child(1) {
  width: 75%;
  height: 14px;
}

.skeleton-line:nth-child(2) {
  width: 45%;
}

.skeleton-line:nth-child(3) {
  width: 55%;
  margin-top: 0.35rem;
}

/* Better shimmer */
.shimmer {
  position: relative;
  overflow: hidden;
}

.shimmer::after {
  content: '';
  position: absolute;
  inset: 0;
  transform: translateX(-100%);
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
  animation: shimmer 1.8s infinite;
}

@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
@keyframes shimmer {
  0% {
    background-position: 200% center;
  }
  100% {
    background-position: -200% center;
  }
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

@media (max-width: 900px) {
  .skeleton-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .skeleton-grid {
    grid-template-columns: 1fr;
  }
}
</style>
