<script setup lang="ts">
import axiosClient from '@/axios'
import { onMounted, ref } from 'vue'
import type { Wishlist } from '@/components/types'
import image from '@/assets/placeholder.jpg'
import { Trash2, Eye, Heart } from 'lucide-vue-next'
import { useToaster } from '@/composables/useToast'
import ProgressSpinner from 'primevue/progressspinner'
import { useRouter } from 'vue-router'

const router = useRouter()
const items = ref<Wishlist[]>([])
const loading = ref(true)
const { showSuccess } = useToaster()

onMounted(async () => {
  try {
    const { data } = await axiosClient.get('/api/wishlist')
    items.value = data
  } finally {
    loading.value = false
  }
})

const removeItem = async (id: number) => {
  try {
    const { data } = await axiosClient.delete(`/api/wishlist/${id}`)
    items.value = items.value.filter((item) => item.id !== id)
    showSuccess(data.message)
  } catch (error) {}
}

const viewProduct = (slug: string) => {
  router.push(`/shop/${slug}`)
}
</script>

<template>
  <section class="wishlist">
    <div class="wishlist-header">
      <div class="wishlist-header-left">
        <Heart :size="20" class="header-icon" />
        <h1 class="wishlist-title">Wishlist</h1>
      </div>
      <span v-if="!loading && items.length" class="wishlist-count"
        >{{ items.length }} item{{ items.length !== 1 ? 's' : '' }}</span
      >
    </div>

    <div v-if="loading" class="wishlist-state">
      <ProgressSpinner style="width: 36px; height: 36px" />
      <p>Loading your wishlist…</p>
    </div>

    <div v-else-if="!items.length" class="wishlist-empty">
      <Heart :size="48" class="empty-icon" />
      <p class="empty-title">Your wishlist is empty</p>
      <p class="empty-sub">Save items you love and find them here anytime.</p>
      <RouterLink to="/shop" class="empty-cta">Browse Shop</RouterLink>
    </div>

    <div v-else class="wishlist-list">
      <div
        v-for="item in items"
        :key="item.id"
        class="wishlist-card"
        @click="viewProduct(item.product.slug)"
      >
        <div class="card-image-wrap">
          <img :src="item.product.thumbnail || image" :alt="item.product.name" class="card-image" />
        </div>

        <div class="card-body">
          <div class="card-tags">
            <span v-for="tag in item.product.tags" :key="tag" class="tag">{{ tag }}</span>
          </div>
          <p class="card-name">{{ item.product.name }}</p>
          <p class="card-price">Rs. {{ item.product.variants[0]?.price }}</p>
        </div>

        <div class="card-actions">
          <RouterLink :to="`/shop/${item.product.slug}`" class="btn-view">
            <Eye :size="16" />
            <span>View</span>
          </RouterLink>
          <button class="btn-remove" @click="removeItem(item.id)" title="Remove from wishlist">
            <Trash2 :size="16" />
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.wishlist {
  padding: 2rem 2.5rem;
  max-width: 860px;
  margin: 0 auto;
}

.wishlist-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border);
}

.wishlist-header-left {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.header-icon {
  color: var(--accent);
}

.wishlist-title {
  font-family: 'Oswald', sans-serif;
  font-size: 1.5rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: var(--ink);
  margin: 0;
}

.wishlist-count {
  font-size: 0.78rem;
  font-family: 'DM Sans', sans-serif;
  color: var(--muted);
  letter-spacing: 0.02em;
}

.wishlist-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 4rem;
  color: var(--muted);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
}

.wishlist-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.6rem;
  margin-top: 4rem;
  text-align: center;
}

.empty-icon {
  color: var(--border);
  margin-bottom: 0.5rem;
}

.empty-title {
  font-family: 'Oswald', sans-serif;
  font-size: 1.2rem;
  color: var(--ink);
  margin: 0;
}

.empty-sub {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--muted);
  margin: 0;
}

.empty-cta {
  margin-top: 1rem;
  padding: 0.55rem 1.4rem;
  border: 1px solid var(--accent);
  color: var(--accent);
  font-family: 'Oswald', sans-serif;
  font-size: 0.85rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  text-decoration: none;
  border-radius: 4px;
  transition:
    background 0.2s,
    color 0.2s;
}

.empty-cta:hover {
  background: var(--accent);
  color: var(--bg);
}

.wishlist-list {
  display: flex;
  flex-direction: column;
  gap: 1px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
}

.wishlist-card {
  display: flex;
  align-items: center;
  gap: 1.6rem;
  padding: 1.4rem 1.6rem;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
  transition: background 0.15s;
}

.wishlist-card:last-child {
  border-bottom: none;
}

.wishlist-card:hover {
  background: rgba(255, 255, 255, 0.03);
  cursor: pointer;
}

.card-image-wrap {
  flex-shrink: 0;
  width: 150px;
  height: 130px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid var(--border);
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Body */
.card-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
  min-width: 0;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.3rem;
}

.tag {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.68rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--muted);
  border: 1px solid var(--border);
  padding: 1px 6px;
  border-radius: 3px;
}

.card-name {
  font-family: 'DM Sans', sans-serif;
  font-weight: 500;
  font-size: 0.95rem;
  color: var(--ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
}

.card-price {
  font-family: 'Oswald', sans-serif;
  font-size: 0.95rem;
  color: var(--accent);
  letter-spacing: 0.02em;
  margin: 0;
}

/* Actions */
.card-actions {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  flex-shrink: 0;
}

.btn-view {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.45rem 0.9rem;
  background: var(--accent);
  color: var(--bg);
  text-decoration: none;
  font-family: 'Oswald', sans-serif;
  font-size: 0.8rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border-radius: 5px;
  transition: opacity 0.15s;
}

.btn-view:hover {
  opacity: 0.85;
}

.btn-remove {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  background: transparent;
  border: 1px solid var(--border);
  border-radius: 5px;
  color: var(--muted);
  cursor: pointer;
  transition:
    border-color 0.15s,
    color 0.15s;
}

.btn-remove:hover {
  border-color: #e74c3c;
  color: #e74c3c;
}
</style>
