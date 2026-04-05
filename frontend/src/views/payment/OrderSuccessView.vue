<script setup lang="ts">
import { useRoute } from 'vue-router'

const route = useRoute()
const paymentIntentId = route.query.payment_intent
// The order may not exist yet (webhook is async), so show a pending state
// Optionally poll GET /orders?payment_intent=xxx until the order appears
</script>

<template>
  <div>
    <h1>Payment successful!</h1>
    <p>Your order is being confirmed. You'll receive a confirmation email shortly.</p>
    <p class="text-muted">Reference: {{ paymentIntentId }}</p>
  </div>
</template>
