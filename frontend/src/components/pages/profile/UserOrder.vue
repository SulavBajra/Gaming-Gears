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
      return 'green'
    case 'Failed':
      return 'red'
    case 'Refunded':
      return 'orange'
    default:
      return 'gray'
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
      <div>Total Orders: {{ meta?.total }}</div>
      <div v-for="order in orders" class="order-card" @click="expandItem(order.id)">
        <div class="order-header">
          <h1>{{ order.order_number }}</h1>
          <div class="order-status">
            <span>{{ order.order_status }}</span>
            <span :style="{ backgroundColor: getPaymentStatusColor(order.payment_status) }">{{
              order.payment_status
            }}</span>
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
              <span class="item-name">X{{ item.quantity }}</span>
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
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.order-list {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 1.2rem;
}

.order-card {
  border: 1px solid #e0e0e0;
  border-radius: 0.8rem;
  padding: 1.2rem;
  width: 100%;
  cursor: pointer;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
}

.order-header h1 {
  font-size: 1.5rem;
}

.order-status {
  display: flex;
  gap: 0.8rem;
  flex-wrap: wrap;
  padding-left: 5px;
}

.order-status span {
  background-color: var(--accent);
  padding: 0.4rem 0.8rem;
  border-radius: 0.4rem;
}

.order-items-description {
  border-top: 1px solid #e0e0e0;
  border-bottom: 1px solid #e0e0e0;
  padding: 1.2rem;
}

.order-items {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
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
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.item-invoice {
  display: flex;
  gap: 0.8rem;
  align-items: center;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  font-weight: 20;
  font-style: italic;
  align-items: center;
  padding-top: 1.2rem;
}

.pagination-part {
  display: flex;
  justify-content: center;
}
</style>
