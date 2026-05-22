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
.order-list-layout {
  height: 100%;
  padding: 1.2rem 2.4rem;

  /* LIGHT MODE (your base palette usage) */
  --bg: #253439;
  --surface: #2f3f44;
  --surface-2: #1b2a2e;

  --text: #ffffff;
  --text-muted: #8a8a8a;

  --border: #e8e4df;
  --hover: rgba(200, 169, 110, 0.08);

  --badge-bg: rgba(200, 169, 110, 0.15);
  --badge-text: #ffffff;

  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;

  background: var(--bg);
  color: var(--text);
}

/* DARK MODE (slightly deeper, not too different) */
@media (prefers-color-scheme: dark) {
  .order-list-layout {
    --bg: #1b2a2e;
    --surface: #223338;
    --surface-2: #152226;

    --text: #ffffff;
    --text-muted: #9aa4a6;

    --border: #2d3e44;
    --hover: rgba(200, 169, 110, 0.06);
  }
}

/* Optional manual theme toggle */
.order-list-layout.dark-mode {
  --bg: #1b2a2e;
  --surface: #223338;
  --surface-2: #152226;

  --text: #ffffff;
  --text-muted: #9aa4a6;

  --border: #2d3e44;
  --hover: rgba(200, 169, 110, 0.06);
}

/* loading */
.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

/* header count */
.total-orders {
  color: var(--text-muted);
  font-weight: 500;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--border);
  margin-bottom: 0.5rem;
}

/* list */
.order-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

/* card */
.order-card {
  border: 1px solid var(--border);
  border-radius: 0.8rem;
  padding: 1.2rem;
  width: 100%;
  cursor: pointer;

  background: var(--surface);
  color: var(--text);

  transition: 0.2s ease;
}

.order-card:hover {
  background: var(--surface-2);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

/* header */
.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
}

.order-header h1 {
  font-size: 1.5rem;
  margin: 0;
  font-weight: 600;
  color: var(--text);
}

/* badges */
.order-status {
  display: flex;
  gap: 0.8rem;
  flex-wrap: wrap;
}

.order-status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 0.4rem;
  font-size: 0.875rem;
  font-weight: 500;

  background: rgba(232, 228, 223, 0.08);
  color: var(--text);
}

.payment-status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 0.4rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: white;
}

/* items */
.order-items-description {
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  padding: 1.2rem;
  margin: 0.5rem 0;
}

.order-items {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
  padding: 0.5rem 0;
  color: var(--text);
}

.order-items:not(:last-child) {
  border-bottom: 1px solid var(--border);
}

/* item */
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
  border: 1px solid var(--border);
}

.item-name {
  font-weight: 500;
}

.item-invoice {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.item-quantity {
  color: var(--text-muted);
}

.item-price {
  font-weight: 600;
}

/* footer */
.order-footer {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  font-style: italic;
  padding-top: 1.2rem;
  color: var(--text-muted);
}

.order-footer p {
  margin: 0;
}

/* pagination */
.pagination-part {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid var(--border);
}

/* responsive */
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
    gap: 0.5rem;
  }
}
</style>
