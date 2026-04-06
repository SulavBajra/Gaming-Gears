<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { loadStripe } from '@stripe/stripe-js'
import axiosClient from '@/axios'

const stripe = ref(null)
const cardElement = ref(null)
const clientSecret = ref('')
const processing = ref(false)

onMounted(async () => {
  const { data } = await axiosClient.post('/api/checkout/intent')
  clientSecret.value = data.client_secret

  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  const elements = stripe.value.elements()

  cardElement.value = elements.create('card', {
    style: { base: { fontFamily: 'DM Sans, sans-serif', fontSize: '16px' } },
  })
  cardElement.value.mount('#card-element')
})

async function submitPayment() {
  processing.value = true

  const { error, paymentIntent } = await stripe.value.confirmCardPayment(clientSecret.value, {
    payment_method: { card: cardElement.value },
  })

  if (error) {
    console.error(error.message)
    processing.value = false
    return
  }

  // PaymentIntent succeeded — redirect to confirmation
  // Actual order creation happens via webhook, not here
  router.push(`/orders/pending?payment_intent=${paymentIntent.id}`)
}
</script>

<template>
  <div id="card-element" class="border rounded-lg p-4" />
  <button @click="submitPayment" :disabled="processing">
    {{ processing ? 'Processing...' : 'Pay Now' }}
  </button>
</template>
