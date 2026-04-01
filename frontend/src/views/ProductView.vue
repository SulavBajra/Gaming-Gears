<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute } from 'vue-router'
import ProductGrid from '@/components/pages/ProductGrid.vue'
import type { HomeProduct, ProductView } from '@/components/types'
import { ShoppingCart, ChevronLeft, ChevronRight } from '@lucide/vue'
import placeholder from '@/assets/placeholder.jpg'
import { useAuth } from '@/composables/useAuth'
import { useCart } from '@/composables/useCart'
import axiosClient from '@/axios'
import { useToaster } from '@/composables/useToast'

const route = useRoute()
const { user } = useAuth()
const { showSuccess } = useToaster()
const { addToCart: addItemToCart, handleGuestAddToCart } = useCart()
const quantity = ref(1)
const product = ref<ProductView | null>(null)
const similarProducts = ref<HomeProduct[]>([])
const selectedVariantId = ref<number | null>(null)
const selectedVariant = computed(() =>
  product.value?.variants.find((v) => v.id === selectedVariantId.value),
)
const loading = ref(true)

// Gallery state
const activeImageIndex = ref(0)
const allImages = computed(() => {
  const imgs: string[] = []
  if (product.value?.thumbnail) imgs.push(product.value.thumbnail)
  if (product.value?.gallery?.length) imgs.push(...product.value.gallery)
  return imgs.length ? imgs : [placeholder]
})
const activeImage = computed(() => allImages.value[activeImageIndex.value])

const prevImage = () => {
  activeImageIndex.value =
    (activeImageIndex.value - 1 + allImages.value.length) % allImages.value.length
}
const nextImage = () => {
  activeImageIndex.value = (activeImageIndex.value + 1) % allImages.value.length
}

const handleAddToCart = async () => {
  if (!selectedVariant.value || !product.value) return
  if (!user.value) {
    handleGuestAddToCart(product.value.id, selectedVariant.value.id, quantity.value)
  }
  const success = await addItemToCart(product.value.id, selectedVariant.value.id, quantity.value)
  if (success) {
    showSuccess()
  }
}

const fetchProduct = async (slug: string) => {
  loading.value = true
  activeImageIndex.value = 0
  try {
    const productRes = await axiosClient.get(`/api/shop/${slug}`)
    product.value = productRes.data.data
    selectedVariantId.value = product.value?.variants?.[0]?.id ?? null
    const catSlug = product.value?.categories?.[0]?.slug
    if (catSlug) {
      const sim = await axiosClient.get(`/api/shop/category/${catSlug}`)
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
        <div class="skeleton-image-col">
          <div class="skeleton skeleton-image"></div>
          <div class="skeleton-thumbs">
            <div class="skeleton skeleton-thumb" v-for="n in 3" :key="n"></div>
          </div>
        </div>
        <div class="skeleton-info">
          <div class="skeleton skeleton-brand"></div>
          <div class="skeleton skeleton-title"></div>
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
        <!-- Main Image -->
        <div class="image-wrap">
          <img :src="activeImage" :alt="product.name" :key="activeImage" />

          <!-- Prev / Next arrows — only shown when multiple images -->
          <template v-if="allImages.length > 1">
            <button class="gallery-arrow left" @click="prevImage" aria-label="Previous image">
              <ChevronLeft :size="18" />
            </button>
            <button class="gallery-arrow right" @click="nextImage" aria-label="Next image">
              <ChevronRight :size="18" />
            </button>
            <div class="gallery-dots">
              <span
                v-for="(_, i) in allImages"
                :key="i"
                class="dot"
                :class="{ active: i === activeImageIndex }"
                @click="activeImageIndex = i"
              />
            </div>
          </template>
        </div>

        <!-- Thumbnails -->
        <div class="thumbnails" v-if="allImages.length > 1">
          <button
            v-for="(img, i) in allImages"
            :key="i"
            class="thumb-btn"
            :class="{ active: i === activeImageIndex }"
            @click="activeImageIndex = i"
          >
            <img :src="img" :alt="`View ${i + 1}`" />
          </button>
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

        <input class="quantity" type="number" min="1" v-model.number="quantity" />
        <button class="add-to-cart" :disabled="!selectedVariant" @click="handleAddToCart">
          <ShoppingCart :size="18" />
          <span>Add to Cart</span>
        </button>
      </div>
    </div>

    <!-- Not Found -->
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
  min-height: 60vh;
  padding: 60px 40px;
  width: 100%;
  margin: 0 auto;
  font-family: 'Outfit', sans-serif;
  background-color: var(--bg);
}

/* ── Product Layout ── */
.product-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  align-items: start;
  animation: fadeUp 0.5s ease both;
  max-width: 1100px;
  margin: 0 auto;
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
  /*position: sticky;*/
  top: 40px;
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.image-wrap {
  position: relative;
  width: 100%;
  aspect-ratio: 4 / 3;
  border-radius: 6px;
  overflow: hidden;
  background: #1c2b2f;
  box-shadow:
    0 4px 24px rgba(0, 0, 0, 0.35),
    0 0 0 1px rgba(255, 255, 255, 0.05);
}

.image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition:
    opacity 0.25s ease,
    transform 0.6s ease;
}

.image-wrap:hover img {
  transform: scale(1.03);
}

/* ── Gallery Arrows ── */
.gallery-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid rgba(255, 255, 255, 0.15);
  background: rgba(37, 52, 57, 0.75);
  backdrop-filter: blur(6px);
  color: #f0ebe2;
  display: grid;
  place-items: center;
  cursor: pointer;
  opacity: 0;
  transition:
    opacity 0.2s,
    background 0.2s;
  z-index: 2;
}

.gallery-arrow.left {
  left: 10px;
}
.gallery-arrow.right {
  right: 10px;
}

.image-wrap:hover .gallery-arrow {
  opacity: 1;
}

.gallery-arrow:hover {
  background: rgba(201, 169, 110, 0.25);
  border-color: #c9a96e;
}

/* ── Dots ── */
.gallery-dots {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 6px;
  z-index: 2;
}

.dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: rgba(240, 235, 226, 0.35);
  cursor: pointer;
  transition:
    background 0.2s,
    transform 0.2s;
}

.dot.active {
  background: #c9a96e;
  transform: scale(1.3);
}

/* ── Thumbnails ── */
.thumbnails {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.thumb-btn {
  width: 64px;
  height: 64px;
  border-radius: 4px;
  overflow: hidden;
  border: 2px solid transparent;
  background: #1c2b2f;
  cursor: pointer;
  padding: 0;
  transition:
    border-color 0.2s,
    opacity 0.2s;
  opacity: 0.55;
  flex-shrink: 0;
}

.thumb-btn img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.thumb-btn:hover {
  opacity: 0.85;
}

.thumb-btn.active {
  border-color: #c9a96e;
  opacity: 1;
}

/* ── Info Panel ── */
.info-panel {
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding-top: 8px;
}

.brand-name {
  font-size: 0.72rem;
  font-weight: 500;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #c9a96e;
}

.product-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(2rem, 4vw, 2rem);
  font-weight: 600;
  line-height: 1.15;
  color: #f0ebe2;
  margin: 0;
}

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
  background: rgba(201, 169, 110, 0.1);
  color: #c9a96e;
  border: 1px solid rgba(201, 169, 110, 0.25);
}

.product-description {
  text-align: justify;
  font-size: 0.925rem;
  font-weight: 300;
  line-height: 1.8;
  color: #8fa8a8;
  margin: 0;
}

/* ── Price ── */
.price-block {
  display: flex;
  align-items: baseline;
  gap: 4px;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
  border-bottom: 1px solid rgba(255, 255, 255, 0.07);
  padding: 20px 0;
}

.currency {
  font-size: 1rem;
  color: #8fa8a8;
}

.price-value {
  font-family: 'Cormorant Garamond', serif;
  font-size: 35px;
  font-weight: 600;
  color: #f0ebe2;
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
  color: #8fa8a8;
}

.select-wrapper {
  position: relative;
}

.variant-select {
  width: 100%;
  padding: 14px 44px 14px 16px;
  font-family: 'Outfit', sans-serif;
  font-size: 0.9rem;
  color: #f0ebe2;
  background: #1c2b2f;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 4px;
  appearance: none;
  cursor: pointer;
  transition: border-color 0.2s;
  outline: none;
}

.variant-select:focus {
  border-color: #c9a96e;
}
.variant-select option {
  background: #1c2b2f;
  color: #f0ebe2;
}

.select-arrow {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.85rem;
  color: #c9a96e;
  pointer-events: none;
}

/* ── Add to Cart ── */
.add-to-cart {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 16px 32px;
  background: #c9a96e;
  color: #1a2428;
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
    transform 0.15s,
    box-shadow 0.2s;
}

.add-to-cart:hover:not(:disabled) {
  background: #dbbf85;
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(201, 169, 110, 0.3);
}

.add-to-cart:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: none;
}

.add-to-cart:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

/* ── Skeleton ── */
.state-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
}

.skeleton-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  width: 100%;
  max-width: 1100px;
}

.skeleton-image-col {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.skeleton-info {
  display: flex;
  flex-direction: column;
  gap: 18px;
  padding-top: 8px;
}

.skeleton {
  background: linear-gradient(90deg, #1c2b2f 25%, #2a3d42 50%, #1c2b2f 75%);
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
  aspect-ratio: 4/3;
  border-radius: 6px;
}
.skeleton-thumbs {
  display: flex;
  gap: 8px;
}
.skeleton-thumb {
  width: 64px;
  height: 64px;
  flex-shrink: 0;
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
}

/* ── Not Found ── */
.not-found {
  text-align: center;
  color: #8fa8a8;
}
.not-found-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 16px;
  opacity: 0.35;
}
.not-found p {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.4rem;
  color: #f0ebe2;
}

/* ── Similar Products ── */
.similar-products {
  max-width: 100%;
  margin: auto;
  padding: 60px 40px;
  background-color: var(--bg);
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
  color: #f0ebe2;
  white-space: nowrap;
  margin: 0;
}

.header-rule {
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.07);
}

/* ── Responsive ── */
@media (max-width: 900px) {
  .product-view {
    padding: 40px 24px;
  }

  .product-layout {
    grid-template-columns: 1fr;
    gap: 32px;
  }

  .image-panel {
    position: static;
  }
  .skeleton-layout {
    grid-template-columns: 1fr;
  }
  .similar-products {
    padding: 40px 24px;
  }
}
</style>
