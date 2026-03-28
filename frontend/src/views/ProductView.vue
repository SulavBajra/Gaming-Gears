<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import ProductGrid from '@/components/pages/ProductGrid.vue'
import type { HomeProduct, ProductView } from '@/components/types'
import { ShoppingCart } from '@lucide/vue'
import { computed } from 'vue'
import placeholder from '@/assets/placeholder.jpg'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
})

const route = useRoute()
const product = ref<ProductView | null>(null)
const similarProducts = ref<HomeProduct[]>([])
const selectedVariantId = ref<number | null>(null)
const selectedVariant = computed(() =>
  product.value?.variants.find((v) => v.id === selectedVariantId.value),
)
const loading = ref(true)

const fetchProduct = async (slug: string) => {
  loading.value = true
  try {
    const productRes = await api.get(`/api/shop/${slug}`)
    product.value = productRes.data.data
    selectedVariantId.value = product.value?.variants?.[0]?.id ?? null
    const catSlug = product.value?.categories?.[0]?.slug
    if (catSlug) {
      const sim = await api.get(`/api/shop/category/${catSlug}`)
      similarProducts.value = sim.data.data
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const slug = route.params.slug as string
  if (slug) fetchProduct(slug)
})

watch(
  () => route.params.slug,
  (slug) => {
    if (slug) fetchProduct(slug as string)
  },
)
</script>

<template>
  <section class="product-view">
    <!-- Loading State -->
    <div v-if="loading" class="state-wrapper">
      <div class="skeleton-layout">
        <div class="skeleton skeleton-image"></div>
        <div class="skeleton-info">
          <div class="skeleton skeleton-title"></div>
          <div class="skeleton skeleton-brand"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text short"></div>
          <div class="skeleton skeleton-price"></div>
          <div class="skeleton skeleton-btn"></div>
        </div>
      </div>
    </div>

    <!-- Product Content -->
    <div v-else-if="product" class="product-layout">
      <!-- Image Panel -->
      <div class="image-panel">
        <div class="image-wrap">
          <img :src="product.thumbnail || placeholder" :alt="product.name" />
        </div>
      </div>

      <!-- Info Panel -->
      <div class="info-panel">
        <div class="brand-line">
          <span class="brand-name">{{ product.brand?.name }}</span>
        </div>

        <h1 class="product-title">{{ product.name }}</h1>

        <div class="tags" v-if="product.tags?.length">
          <span v-for="tag in product.tags" :key="tag" class="tag">{{ tag }}</span>
        </div>

        <p class="product-description">{{ product.description }}</p>

        <div class="price-block">
          <span class="currency">Rs.</span>
          <span class="price-value">{{
            (selectedVariant?.price ?? product.price).toLocaleString()
          }}</span>
        </div>

        <div class="variant-block" v-if="product.variants?.length">
          <label class="variant-label">Select Variant</label>
          <div class="select-wrapper">
            <select v-model="selectedVariantId" class="variant-select">
              <option disabled value="">Choose an option</option>
              <option v-for="variant in product.variants" :key="variant.id" :value="variant.id">
                {{ variant.name }} — Rs. {{ variant.price.toLocaleString() }}
              </option>
            </select>
            <span class="select-arrow">↓</span>
          </div>
        </div>

        <button class="add-to-cart" :disabled="!selectedVariant">
          <ShoppingCart :size="18" />
          <span>Add to Cart</span>
        </button>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="state-wrapper">
      <div class="not-found">
        <span class="not-found-icon">⊘</span>
        <p>Product not found</p>
      </div>
    </div>
  </section>

  <!-- Similar Products -->
  <section v-if="similarProducts.length" class="similar-products">
    <div class="similar-header">
      <h2>Similar Products</h2>
      <div class="header-rule"></div>
    </div>
    <ProductGrid :products="similarProducts" />
  </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Outfit:wght@300;400;500&display=swap');

.product-view {
  background-color: var(--bg);
  min-height: 60vh;
  padding: 60px 40px;
  max-width: 1200px;
  margin: 0 auto;
  font-family: 'Outfit', sans-serif;
}

/* ── Product Layout ── */
.product-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: start;
  animation: fadeUp 0.5s ease both;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ── Image Panel ── */
.image-panel {
  position: sticky;
  top: 40px;
}

.image-wrap {
  aspect-ratio: 1 / 1;
  border-radius: 4px;
  overflow: hidden;
  background: var(--surface, #f5f2ee);
  box-shadow: 0 2px 32px rgba(0, 0, 0, 0.08);
}

.image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.image-wrap:hover img {
  transform: scale(1.04);
}

/* ── Info Panel ── */
.info-panel {
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding-top: 8px;
}

.brand-line {
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand-name {
  font-size: 0.72rem;
  font-weight: 500;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--accent, #b07d4e);
}

.product-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(2rem, 4vw, 2.8rem);
  font-weight: 600;
  line-height: 1.15;
  color: var(--text, #1a1612);
  margin: 0;
}

/* ── Tags ── */
.tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tag {
  font-size: 0.72rem;
  font-weight: 400;
  letter-spacing: 0.06em;
  padding: 4px 12px;
  border-radius: 100px;
  background: var(--tag-bg, rgba(176, 125, 78, 0.1));
  color: var(--accent, #b07d4e);
  border: 1px solid rgba(176, 125, 78, 0.2);
}

/* ── Description ── */
.product-description {
  font-size: 0.925rem;
  font-weight: 300;
  line-height: 1.8;
  color: var(--muted, #6b6259);
  margin: 0;
  max-width: 480px;
}

/* ── Price ── */
.price-block {
  display: flex;
  align-items: baseline;
  gap: 4px;
  border-top: 1px solid var(--divider, rgba(0, 0, 0, 0.08));
  border-bottom: 1px solid var(--divider, rgba(0, 0, 0, 0.08));
  padding: 20px 0;
}

.currency {
  font-size: 1rem;
  font-weight: 400;
  color: var(--muted, #6b6259);
}

.price-value {
  font-family: 'Cormorant Garamond', serif;
  font-size: 2.2rem;
  font-weight: 600;
  color: var(--text, #1a1612);
  line-height: 1;
}

/* ── Variant Select ── */
.variant-block {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.variant-label {
  font-size: 0.72rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted, #6b6259);
}

.select-wrapper {
  position: relative;
}

.variant-select {
  width: 100%;
  padding: 14px 44px 14px 16px;
  font-family: 'Outfit', sans-serif;
  font-size: 0.9rem;
  font-weight: 400;
  color: var(--text, #1a1612);
  background: var(--surface, #f5f2ee);
  border: 1px solid var(--divider, rgba(0, 0, 0, 0.12));
  border-radius: 4px;
  appearance: none;
  cursor: pointer;
  transition: border-color 0.2s;
  outline: none;
}

.variant-select:focus {
  border-color: var(--accent, #b07d4e);
}

.select-arrow {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.85rem;
  color: var(--accent, #b07d4e);
  pointer-events: none;
}

/* ── Add to Cart ── */
.add-to-cart {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 16px 32px;
  background: var(--text, #1a1612);
  color: var(--bg, #faf8f5);
  font-family: 'Outfit', sans-serif;
  font-size: 0.875rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition:
    background 0.2s,
    transform 0.15s;
}

.add-to-cart:hover:not(:disabled) {
  background: var(--accent, #b07d4e);
  transform: translateY(-1px);
}

.add-to-cart:active:not(:disabled) {
  transform: translateY(0);
}

.add-to-cart:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* ── Loading Skeleton ── */
.state-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
}

.skeleton-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  width: 100%;
}

.skeleton-info {
  display: flex;
  flex-direction: column;
  gap: 18px;
  padding-top: 8px;
}

.skeleton {
  background: linear-gradient(
    90deg,
    var(--surface, #f0ece6) 25%,
    var(--divider, #e5e0d8) 50%,
    var(--surface, #f0ece6) 75%
  );
  background-size: 200% 100%;
  animation: shimmer 1.4s infinite;
  border-radius: 4px;
}

@keyframes shimmer {
  from {
    background-position: 200% 0;
  }
  to {
    background-position: -200% 0;
  }
}

.skeleton-image {
  aspect-ratio: 1/1;
  border-radius: 4px;
}
.skeleton-title {
  height: 52px;
  width: 80%;
}
.skeleton-brand {
  height: 14px;
  width: 30%;
}
.skeleton-text {
  height: 14px;
  width: 100%;
}
.skeleton-text.short {
  width: 65%;
}
.skeleton-price {
  height: 40px;
  width: 45%;
}
.skeleton-btn {
  height: 52px;
  border-radius: 4px;
}

/* ── Not Found ── */
.not-found {
  text-align: center;
  color: var(--muted, #6b6259);
}

.not-found-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 16px;
  opacity: 0.4;
}

.not-found p {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.4rem;
}

/* ── Similar Products ── */
.similar-products {
  background-color: var(--bg);
  max-width: 1200px;
  margin: 80px auto 60px;
  padding: 0 40px;
}

.similar-header {
  display: flex;
  align-items: center;
  gap: 24px;
  margin-bottom: 40px;
}

.similar-header h2 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--text, #1a1612);
  white-space: nowrap;
  margin: 0;
}

.header-rule {
  flex: 1;
  height: 1px;
  background: var(--divider, rgba(0, 0, 0, 0.1));
}

/* ── Responsive ── */
@media (max-width: 900px) {
  .product-view {
    padding: 40px 24px;
  }

  .product-layout {
    grid-template-columns: 1fr;
    gap: 36px;
  }

  .image-panel {
    position: static;
  }

  .skeleton-layout {
    grid-template-columns: 1fr;
  }

  .similar-products {
    padding: 0 24px;
  }
}
</style>
