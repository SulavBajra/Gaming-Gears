<script setup lang="ts">
import type { Brand } from '@/components/types'
defineProps<{
  brands: Brand[]
}>()

function getInitials(name: string): string {
  return name
    .split(' ')
    .map((w) => w[0])
    .join('')
    .slice(0, 2)
    .toUpperCase()
}
</script>

<template>
  <section class="brands-section">
    <h2>Our brands</h2>
    <div class="carousel-wrapper">
      <div class="carousel-track">
        <RouterLink
          v-for="brand in [...brands, ...brands]"
          :key="`${brand.id}-${brand.slug}`"
          class="brand-card"
          :to="{ path: '/shop', query: { brand: brand.slug } }"
        >
          <div v-if="brand.logo_url" class="brand-logo">
            <img :src="brand.logo_url" :alt="brand.name" />
          </div>
          <div v-else class="brand-fallback">
            {{ getInitials(brand.name) }}
          </div>
          <span class="brand-name">{{ brand.name }}</span>
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<style scoped>
.brands-section {
  padding: 2.5rem 2rem;
  text-align: center;
  border-top: dashed #ffff;
  padding-top: 1.5rem;
}

.brands-section h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 2.2rem;
  font-weight: 500;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--ink);
  margin-bottom: 1.5rem;
}

.carousel-wrapper {
  position: relative;
  overflow: hidden;
}

.carousel-wrapper::before,
.carousel-wrapper::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 80px;
  z-index: 2;
  pointer-events: none;
}

.carousel-wrapper::before {
  left: 0;
  background: linear-gradient(to right, var(--bg), transparent);
}

.carousel-wrapper::after {
  right: 0;
  background: linear-gradient(to left, var(--bg), transparent);
}

.carousel-track {
  display: flex;
  gap: 16px;
  width: max-content;
  animation: scroll 20s linear infinite;
}

.carousel-track:hover {
  animation-play-state: paused;
}

.brand-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 1rem 1.25rem;
  border: 1px solid var(--border);
  border-radius: 12px;
  background: var(--bg);
  width: 200px;
  height: 120px;
  flex-shrink: 0;
  transition: border-color 0.15s;
}

.brand-card:hover {
  border-color: var(--accent);
  cursor: pointer;
}

.brand-logo {
  width: 70px;
  height: 72px;
  border-radius: 8px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.brand-fallback {
  width: 52px;
  height: 52px;
  border-radius: 8px;
  background: color-mix(in srgb, var(--accent) 10%, transparent);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  color: var(--accent);
}

.brand-name {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.75rem;
  color: var(--muted);
  white-space: nowrap;
}

@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>
