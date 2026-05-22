<script setup lang="ts">
import axiosClient from '@/axios'
import { onMounted, ref } from 'vue'
import type { Order, Links, Meta } from '@/components/types'
import ProgressSpinner from 'primevue/progressspinner'
import placeholderImage from '@/assets/placeholder.jpg'
import Paginator from 'primevue/paginator'

const orders = ref<Order[]>([])
const links = ref<Links>()
const meta = ref<Meta>()
const loading = ref(false)
const expandedId = ref<number | null>(null)

const getPaymentStatusColor = (status: string) => {
  switch (status) {
    case 'Paid':
      return 'var(--status-success, #10b981)'
    case 'Failed':
      return 'var(--status-danger, #ef4444)'
    case 'Refunded':
      return 'var(--status-warning, #f59e0b)'
    default:
      return 'var(--status-neutral, #6b7280)'
  }
}

const expandItem = (id: number) => {
  expandedId.value = expandedId.value === id ? null : id
}

const onPageChange = async (event: { page: number }) => {
  loading.value = true
  const response = await axiosClient.get(`/api/orders?page=${event.page + 1}`)
  orders.value = response.data.data
  links.value = response.data.links
  meta.value = response.data.meta
  loading.value = false
}
const fetchOrder = async () => {
  loading.value = true
  const response = await axiosClient.get('/api/orders')
  orders.value = response.data.data
  links.value = response.data.links
  meta.value = response.data.meta
  loading.value = false
}

onMounted(() => {
  fetchOrder()
})
</script>

<template>
  <div class="order-list-layout">
    <div v-if="loading" class="loading"><ProgressSpinner /></div>

    <div v-else class="order-list">
      <div class="total-orders">Total Orders: {{ meta?.total }}</div>
      <div v-for="order in orders" class="order-card" @click="expandItem(order.id)">
        <div class="order-header">
          <h1>{{ order.order_number }}</h1>
          <div class="order-status">
            <span class="order-status-badge">{{ order.order_status }}</span>
            <span
              class="payment-status-badge"
              :style="{ backgroundColor: getPaymentStatusColor(order.payment_status) }"
            >
              {{ order.payment_status }}
            </span>
          </div>
        </div>

        <div v-if="expandedId === order.id" class="order-items-description">
          <div v-for="item in order.items" class="order-items">
            <div class="item-details">
              <img
                :src="item.image || placeholderImage"
                alt="{{ item.product_name }}"
                class="item-image"
              />
              <span class="item-name">{{ item.product_name }}</span>
            </div>
            <div class="item-invoice">
              <span class="item-quantity">X{{ item.quantity }}</span>
              <span class="item-price">Rs.{{ item.unit_price * item.quantity }}</span>
            </div>
          </div>
        </div>

        <div class="order-footer">
          <p>{{ order.created_at }}</p>
          <p>Total: Rs {{ order.total }}</p>
        </div>
      </div>
    </div>
    <div class="pagination-part">
      <Paginator
        v-if="meta && meta.last_page > 1"
        :first="(meta.current_page - 1) * meta.per_page"
        :rows="meta.per_page"
        :totalRecords="meta.total"
        template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        @page="onPageChange"
      />
    </div>
  </div>
</template>

<style scoped>
/* CSS Custom Properties for theme support */
.order-list-layout {
  height: 100%;
  padding: 1.2rem 2.4rem;
  --text-primary: #1f2937;
  --text-secondary: #4b5563;
  --border-color: #e5e7eb;
  --bg-card: #ffffff;
  --bg-hover: #f9fafb;
  --badge-bg: #e5e7eb;
  --badge-text: #1f2937;
}

/* Dark mode overrides */
@media (prefers-color-scheme: dark) {
  .order-list-layout {
    --text-primary: #f3f4f6;
    --text-secondary: #9ca3af;
    --border-color: #374151;
    --bg-card: #1f2937;
    --bg-hover: #374151;
    --badge-bg: #374151;
    --badge-text: #f3f4f6;
  }
}

/* Optional: Support for manual theme switching via class */
.order-list-layout.dark-mode {
  --text-primary: #f3f4f6;
  --text-secondary: #9ca3af;
  --border-color: #374151;
  --bg-card: #1f2937;
  --bg-hover: #374151;
  --badge-bg: #374151;
  --badge-text: #f3f4f6;
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.total-orders {
  color: var(--text-secondary);
  font-weight: 500;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid var(--border-color);
  margin-bottom: 0.5rem;
}

.order-list {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 1.2rem;
}

.order-card {
  border: 1px solid var(--border-color);
  border-radius: 0.8rem;
  padding: 1.2rem;
  width: 100%;
  cursor: pointer;
  background-color: var(--bg-card);
  color: var(--text-primary);
  transition: all 0.2s ease;
}

.order-card:hover {
  background-color: var(--bg-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
}

.order-header h1 {
  font-size: 1.5rem;
  color: var(--text-primary);
  margin: 0;
  font-weight: 600;
}

.order-status {
  display: flex;
  gap: 0.8rem;
  flex-wrap: wrap;
  padding-left: 5px;
}

.order-status-badge,
.payment-status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 0.4rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.order-status-badge {
  background-color: var(--badge-bg);
  color: var(--badge-text);
}

.payment-status-badge {
  color: white;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}

.order-items-description {
  border-top: 1px solid var(--border-color);
  border-bottom: 1px solid var(--border-color);
  padding: 1.2rem;
  margin: 0.5rem 0;
}

.order-items {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
  color: var(--text-primary);
  padding: 0.5rem 0;
}

.order-items:not(:last-child) {
  border-bottom: 1px solid var(--border-color);
}

.item-details {
  display: flex;
  gap: 0.8rem;
  align-items: center;
}

.item-image {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid var(--border-color);
  flex-shrink: 0;
}

.item-name {
  color: var(--text-primary);
  font-weight: 500;
}

.item-invoice {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.item-quantity {
  color: var(--text-secondary);
  font-weight: 500;
}

.item-price {
  color: var(--text-primary);
  font-weight: 600;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  font-style: italic;
  align-items: center;
  padding-top: 1.2rem;
  color: var(--text-secondary);
}

.order-footer p {
  margin: 0;
}

.pagination-part {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid var(--border-color);
}

/* Responsive design */
@media (max-width: 768px) {
  .order-list-layout {
    padding: 1rem;
  }

  .order-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .order-header h1 {
    font-size: 1.2rem;
  }

  .order-items {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .item-invoice {
    width: 100%;
    justify-content: space-between;
  }

  .order-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
