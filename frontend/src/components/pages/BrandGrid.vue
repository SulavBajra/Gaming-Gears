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
    <p class="section-eyebrow">Partners</p>
    <h2>Brands we carry</h2>
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
  padding: 3rem 2rem;
  text-align: center;
}

.section-eyebrow {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.72rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 0.5rem;
}

.brands-section h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 1.8rem;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--ink);
  margin-bottom: 2rem;
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
  gap: 12px;
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
  justify-content: center;
  gap: 10px;
  padding: 1.25rem 1rem;
  border: 1px solid var(--border);
  border-radius: 12px;
  background: var(--bg);
  width: 140px;
  height: 100px;
  flex-shrink: 0;
  text-decoration: none;
  transition:
    border-color 0.15s,
    background 0.15s;
}

.brand-card:hover {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 4%, var(--bg));
}

.brand-logo {
  width: 80px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.brand-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: center;
  /* makes dark logos visible in dark mode */
  filter: brightness(0) invert(var(--logo-invert, 0));
}

.brand-fallback {
  width: 44px;
  height: 44px;
  border-radius: 8px;
  background: color-mix(in srgb, var(--accent) 10%, transparent);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Oswald', sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--accent);
}

.brand-name {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.75rem;
  font-weight: 500;
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
