<script setup lang="ts">
import { useCart } from '@/composables/useCart'
import { useAuth } from '@/composables/useAuth'
import { RouterLink, useRouter } from 'vue-router'
import { onMounted } from 'vue'
import { Trash2, ShoppingBag, ArrowRight, Minus, Plus } from '@lucide/vue'
import placeholder from '@/assets/placeholder.jpg'
import ProgressSpinner from 'primevue/progressspinner'
import { useConfirmDialog } from '@/composables/useConfirm'

const { deleteCartItem, clearEntireCart } = useConfirmDialog()
const { fetchCart, cart, items, isEmpty, totalItems, totalPrice, updateQuantity, loading } =
  useCart()
const { user } = useAuth()
const router = useRouter()

onMounted(async () => {
  await fetchCart()
})

const decrease = (item: (typeof items.value)[number]) => {
  if (item.quantity <= 1) return
  updateQuantity(item.id, item.quantity - 1)
}

const increase = (item: (typeof items.value)[number]) => {
  if (item.quantity <= 1) return
  updateQuantity(item.id, item.quantity + 1)
}

const handleCheckout = () => {
  if (!user.value) {
    router.push('/login')
    return
  }
  router.push('/checkout')
}
</script>

<template>
  <section class="cart-page">
    <div class="cart-container">
      <div class="cart-header">
        <h1>Your Cart</h1>
        <span class="cart-count" v-if="!isEmpty"
          >{{ totalItems }} {{ totalItems === 1 ? 'item' : 'items' }}</span
        >
      </div>

      <div v-if="loading" class="state-box">
        <ProgressSpinner />
        <div class="skeleton-rows">
          <div class="skeleton-row" v-for="n in 3" :key="n">
            <div class="skeleton sk-img"></div>
            <div class="sk-info">
              <div class="skeleton sk-title"></div>
              <div class="skeleton sk-sub"></div>
            </div>
            <div class="skeleton sk-price"></div>
          </div>
        </div>
      </div>

      <div v-else-if="isEmpty" class="state-box empty-state">
        <ShoppingBag :size="48" class="empty-icon" />
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added anything yet.</p>
        <RouterLink to="/shop" class="btn-primary">Browse Products</RouterLink>
      </div>
      <div v-else class="cart-layout">
        <div class="cart-items">
          <div class="cart-item" v-for="item in items" :key="item.id">
            <div class="item-image">
              <img
                :src="item.thumbnail ?? placeholder"
                @error="(e) => (e.target.src = placeholder)"
              />
            </div>

            <div class="item-info">
              <p class="item-name">{{ item.product_name }}</p>
              <p class="item-variant" v-if="item.product_variant_name">
                {{ item.product_variant_name }}
              </p>
              <p class="item-unit-price">Rs. {{ item.unit_price.toLocaleString() }} each</p>
            </div>

            <div class="item-qty">
              <button class="qty-btn" @click="decrease(item)" :disabled="loading">
                <Minus :size="13" />
              </button>
              <span class="qty-value">{{ item.quantity }}</span>
              <button class="qty-btn" @click="increase(item)" :disabled="loading">
                <Plus :size="13" />
              </button>
            </div>
            <div class="item-right">
              <span class="item-total">Rs. {{ item.item_total_price.toLocaleString() }}</span>
              <button
                class="remove-btn"
                @click="deleteCartItem(item.id)"
                :disabled="loading"
                aria-label="Remove item"
              >
                <Trash2 :size="15" />
              </button>
            </div>
          </div>

          <div class="cart-actions">
            <button
              v-if="cart?.id"
              class="btn-ghost"
              @click="clearEntireCart(cart.id)"
              :disabled="loading"
            >
              Clear cart
            </button>
          </div>
        </div>

        <aside class="cart-summary">
          <h2>Order Summary</h2>

          <div class="summary-rows">
            <div class="summary-row">
              <span>Subtotal ({{ totalItems }} items)</span>
              <span>Rs. {{ totalPrice.toLocaleString() }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span class="muted">Calculated at checkout</span>
            </div>
          </div>

          <div class="summary-total">
            <span>Total</span>
            <span>Rs. {{ totalPrice.toLocaleString() }}</span>
          </div>

          <button class="btn-primary checkout-btn" @click="handleCheckout" :disabled="loading">
            Checkout
            <ArrowRight :size="16" />
          </button>

          <RouterLink to="/shop" class="continue-link">← Continue Shopping</RouterLink>
        </aside>
      </div>
    </div>
  </section>
</template>

<style scoped>
.cart-page {
  min-height: 80vh;
  padding: 60px 40px;
  background: var(--bg);
  font-family: 'DM Sans', sans-serif;
}

.cart-container {
  max-width: 1100px;
  margin: 0 auto;
}

/* Header */
.cart-header {
  display: flex;
  align-items: baseline;
  gap: 1rem;
  margin-bottom: 2.5rem;
  border-bottom: 1px solid var(--border);
  padding-bottom: 1.25rem;
}

.cart-header h1 {
  font-family: 'Oswald', sans-serif;
  font-size: 2rem;
  font-weight: 500;
  color: var(--ink);
  margin: 0;
}

.cart-count {
  font-size: 0.8rem;
  color: var(--muted);
  letter-spacing: 0.05em;
}

/* Layout */
.cart-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 2.5rem;
  align-items: start;
}

/* Items */
.cart-items {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.cart-item {
  display: grid;
  grid-template-columns: 80px 1fr auto auto;
  gap: 1.25rem;
  align-items: center;
  padding: 1.25rem 0;
  border-bottom: 1px solid var(--border);
}

.item-image {
  width: 80px;
  height: 80px;
  border-radius: 6px;
  overflow: hidden;
  background: #1c2b2f;
  flex-shrink: 0;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.item-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.item-name {
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--ink);
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.item-variant {
  font-size: 0.78rem;
  color: var(--muted);
  margin: 0;
}

.item-unit-price {
  font-size: 0.78rem;
  color: var(--muted);
  margin: 0;
}

/* Quantity */
.item-qty {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 4px 8px;
}

.qty-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--muted);
  display: flex;
  align-items: center;
  padding: 2px;
  border-radius: 3px;
  transition:
    color 0.15s,
    background 0.15s;
}

.qty-btn:hover:not(:disabled) {
  color: var(--ink);
  background: var(--border);
}

.qty-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.qty-value {
  font-size: 0.875rem;
  color: var(--ink);
  min-width: 20px;
  text-align: center;
}

/* Right col */
.item-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 8px;
}

.item-total {
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--ink);
  white-space: nowrap;
}

.remove-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--muted);
  display: flex;
  align-items: center;
  padding: 4px;
  border-radius: 4px;
  transition:
    color 0.15s,
    background 0.15s;
}

.remove-btn:hover:not(:disabled) {
  color: #f87171;
  background: rgba(248, 113, 113, 0.08);
}

/* Cart actions */
.cart-actions {
  padding-top: 1rem;
  display: flex;
  justify-content: flex-end;
}

/* Summary */
.cart-summary {
  background: #1c2b2f;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 1.75rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  position: sticky;
  top: 84px;
}

.cart-summary h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--ink);
  margin: 0;
}

.summary-rows {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  color: var(--muted);
}

.summary-total {
  display: flex;
  justify-content: space-between;
  font-size: 1rem;
  font-weight: 500;
  color: var(--ink);
  border-top: 1px solid var(--border);
  padding-top: 1rem;
}

/* Buttons */
.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px 24px;
  background: var(--accent);
  color: #1a2428;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  text-decoration: none;
  transition:
    opacity 0.15s,
    transform 0.15s;
}

.btn-primary:hover:not(:disabled) {
  opacity: 0.88;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.checkout-btn {
  width: 100%;
}

.btn-ghost {
  background: none;
  border: 1px solid var(--border);
  color: var(--muted);
  font-size: 0.8rem;
  padding: 6px 14px;
  border-radius: 5px;
  cursor: pointer;
  transition:
    color 0.15s,
    border-color 0.15s;
}

.btn-ghost:hover:not(:disabled) {
  color: #f87171;
  border-color: rgba(248, 113, 113, 0.4);
}

.continue-link {
  font-size: 0.8rem;
  color: var(--muted);
  text-align: center;
  text-decoration: none;
  transition: color 0.15s;
}

.continue-link:hover {
  color: var(--ink);
}

/* Empty state */
.state-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 40vh;
  gap: 1rem;
  text-align: center;
}

.empty-icon {
  color: var(--muted);
  opacity: 0.4;
}

.empty-state h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 1.5rem;
  font-weight: 400;
  color: var(--ink);
  margin: 0;
}

.empty-state p {
  font-size: 0.875rem;
  color: var(--muted);
  margin: 0;
}

/* Skeleton */
.skeleton-rows {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
}

.skeleton-row {
  display: grid;
  grid-template-columns: 80px 1fr 80px;
  gap: 1.25rem;
  align-items: center;
  padding: 1.25rem 0;
  border-bottom: 1px solid var(--border);
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

.sk-img {
  width: 80px;
  height: 80px;
  border-radius: 6px;
}
.sk-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.sk-title {
  height: 16px;
  width: 60%;
}
.sk-sub {
  height: 12px;
  width: 35%;
}
.sk-price {
  height: 16px;
  width: 70px;
}

/* Responsive */
@media (max-width: 768px) {
  .cart-page {
    padding: 40px 20px;
  }

  .cart-layout {
    grid-template-columns: 1fr;
  }

  .cart-summary {
    position: static;
  }

  .cart-item {
    grid-template-columns: 64px 1fr;
    grid-template-rows: auto auto;
  }

  .item-qty {
    grid-column: 2;
  }

  .item-right {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    grid-column: 1 / -1;
  }
}
</style>
