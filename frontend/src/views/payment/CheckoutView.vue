<script setup lang="ts">
import { ref, reactive, onMounted, onUnmounted, nextTick } from 'vue'
import { loadStripe } from '@stripe/stripe-js'
import { useRouter } from 'vue-router'
import { useCart } from '@/composables/useCart'
import { useAuth } from '@/composables/useAuth'
import axiosClient from '@/axios'
import { ArrowLeft, ArrowRight, Lock } from '@lucide/vue'
import ProgressSpinner from 'primevue/progressspinner'
import { useToaster } from '@/composables/useToast'

const router = useRouter()
const { items, totalPrice, totalItems } = useCart()
const { profile, getUserProfile } = useAuth()
const { showSuccess } = useToaster()

type Step = 'shipping' | 'payment'
const step = ref<Step>('shipping')
const processing = ref(false)
const loadingIntent = ref(false)
const stripeError = ref('')
const clientSecret = ref('')

let stripe: any = null
let cardElement: any = null

const shipping = reactive({
  shipping_name: '',
  shipping_line1: '',
  shipping_city: '',
})

onMounted(async () => {
  await getUserProfile()
  if (profile.value) {
    shipping.shipping_name =
      profile.value.address.first_name + ' ' + profile.value.address.last_name
    shipping.shipping_line1 = profile.value.address.address_line_1
    shipping.shipping_city = profile.value.address.city
  }
})

onUnmounted(() => {
  cardElement?.destroy()
})

async function proceedToPayment() {
  loadingIntent.value = true
  stripeError.value = ''

  try {
    const { data } = await axiosClient.post('/api/checkout/intent', shipping)
    clientSecret.value = data.client_secret
    step.value = 'payment'

    await nextTick()
    await mountCardElement()
  } catch (err: any) {
    stripeError.value = err.response?.data?.message ?? 'Failed to initialize payment.'
    console.log(err.response)
    console.log('baseURL:', axiosClient.defaults.baseURL)
    console.log('token:', localStorage.getItem('token'))
  } finally {
    loadingIntent.value = false
  }
}

async function mountCardElement() {
  stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  const elements = stripe.elements()

  cardElement = elements.create('card', {
    style: {
      base: {
        fontFamily: '"DM Sans", sans-serif',
        fontSize: '15px',
        color: '#e2d9c5',
        iconColor: '#c8a96e',
        '::placeholder': { color: '#5a7070' },
      },
      invalid: { color: '#f87171', iconColor: '#f87171' },
    },
  })

  cardElement.mount('#card-element')

  cardElement.on('change', (e: any) => {
    stripeError.value = e.error?.message ?? ''
  })
}

async function submitPayment() {
  if (!stripe || !cardElement) return
  processing.value = true
  stripeError.value = ''

  const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret.value, {
    payment_method: { card: cardElement },
  })

  if (error) {
    stripeError.value = error.message ?? 'Payment failed.'
    processing.value = false
    return
  }
  showSuccess('Order placed successfully!')
  router.push({ name: 'shop' })
}
</script>

<template>
  <section class="checkout-page">
    <div class="checkout-container">
      <div class="checkout-header">
        <button
          class="back-btn"
          @click="step === 'payment' ? (step = 'shipping') : router.push('/cart')"
        >
          <ArrowLeft :size="16" />
          {{ step === 'payment' ? 'Back to shipping' : 'Back to cart' }}
        </button>
        <h1>Checkout</h1>
      </div>

      <div class="steps">
        <div class="step" :class="{ active: step === 'shipping', done: step === 'payment' }">
          <span class="step-dot">1</span>
          <span>Shipping</span>
        </div>
        <div class="step-line" />
        <div class="step" :class="{ active: step === 'payment' }">
          <span class="step-dot">2</span>
          <span>Payment</span>
        </div>
      </div>

      <div class="checkout-layout">
        <!-- ── Left: form area ── -->
        <div class="checkout-form">
          <!-- Step 1: Shipping -->
          <div v-if="step === 'shipping'" class="form-section">
            <h2>Shipping information</h2>

            <div class="field">
              <label>Full name</label>
              <input v-model="shipping.shipping_name" placeholder="John Doe" />
            </div>
            <div class="field">
              <label>Address</label>
              <input v-model="shipping.shipping_line1" placeholder="123 Street, Area" />
            </div>
            <div class="field-row">
              <div class="field">
                <label>City</label>
                <input v-model="shipping.shipping_city" placeholder="Kathmandu" />
              </div>
            </div>

            <p v-if="stripeError" class="error-msg">{{ stripeError }}</p>

            <button
              class="btn-primary"
              @click="proceedToPayment"
              :disabled="
                loadingIntent ||
                !shipping.shipping_name ||
                !shipping.shipping_line1 ||
                !shipping.shipping_city
              "
            >
              <ProgressSpinner v-if="loadingIntent" style="width: 18px; height: 18px" />
              <template v-else>
                Continue to payment
                <ArrowRight :size="16" />
              </template>
            </button>
          </div>

          <!-- Step 2: Payment -->
          <div v-else class="form-section">
            <h2>Payment details</h2>

            <div class="shipping-summary">
              <p class="summary-label">Shipping to</p>
              <p class="summary-value">
                {{ shipping.shipping_name }} — {{ shipping.shipping_line1 }},
                {{ shipping.shipping_city }}
              </p>
            </div>

            <div class="field">
              <label>Card details</label>
              <div id="card-element" class="card-element-box" />
            </div>

            <p v-if="stripeError" class="error-msg">{{ stripeError }}</p>

            <button class="btn-primary" @click="submitPayment" :disabled="processing">
              <ProgressSpinner v-if="processing" style="width: 18px; height: 18px" />
              <template v-else>
                <Lock :size="15" />
                Pay Rs. {{ totalPrice.toLocaleString() }}
              </template>
            </button>

            <p class="secure-note">
              <Lock :size="12" />
              Payments secured by Stripe. Your card details never touch our servers.
            </p>
          </div>
        </div>

        <!-- ── Right: order summary ── -->
        <aside class="order-summary">
          <h2>Order summary</h2>
          <div class="summary-items">
            <div class="summary-item" v-for="item in items" :key="item.id">
              <div class="si-img">
                <img :src?="item.thumbnail" />
                <span class="si-qty">{{ item.quantity }}</span>
              </div>
              <div class="si-info">
                <p class="si-name">{{ item.product_name }}</p>
                <p class="si-variant" v-if="item.product_variant_name">
                  {{ item.product_variant_name }}
                </p>
              </div>
              <span class="si-price">Rs. {{ item.item_total_price.toLocaleString() }}</span>
            </div>
          </div>

          <div class="summary-rows">
            <div class="summary-row">
              <span>Subtotal ({{ totalItems }} items)</span>
              <span>Rs. {{ totalPrice.toLocaleString() }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span class="muted">Free</span>
            </div>
          </div>

          <div class="summary-total">
            <span>Total</span>
            <span>Rs. {{ totalPrice.toLocaleString() }}</span>
          </div>
        </aside>
      </div>
    </div>
  </section>
</template>

<style scoped>
.checkout-page {
  min-height: 80vh;
  padding: 60px 40px;
  background: var(--bg);
  font-family: 'DM Sans', sans-serif;
}

.checkout-container {
  max-width: 1100px;
  margin: 0 auto;
}

/* Header */
.checkout-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
  border-bottom: 1px solid var(--border);
  padding-bottom: 1.25rem;
}

.checkout-header h1 {
  font-family: 'Oswald', sans-serif;
  font-size: 2rem;
  font-weight: 500;
  color: var(--ink);
  margin: 0;
}

.back-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  color: var(--muted);
  font-size: 0.8rem;
  cursor: pointer;
  padding: 0;
  transition: color 0.15s;
}

.back-btn:hover {
  color: var(--ink);
}

/* Steps */
.steps {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 2.5rem;
}

.step {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.8rem;
  color: var(--muted);
  transition: color 0.2s;
}

.step.active {
  color: var(--accent);
}
.step.done {
  color: var(--ink);
}

.step-dot {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  transition:
    background 0.2s,
    border-color 0.2s;
}

.step.active .step-dot {
  background: var(--accent);
  border-color: var(--accent);
  color: #1a2428;
  font-weight: 600;
}

.step.done .step-dot {
  background: var(--border);
  border-color: var(--border);
}

.step-line {
  flex: 1;
  max-width: 60px;
  height: 1px;
  background: var(--border);
}

/* Layout */
.checkout-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 2.5rem;
  align-items: start;
}

/* Form */
.form-section {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-section h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--ink);
  margin: 0;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.field label {
  font-size: 0.78rem;
  color: var(--muted);
  letter-spacing: 0.04em;
}

.field input {
  background: #1c2b2f;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 11px 14px;
  color: var(--ink);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
  outline: none;
  transition: border-color 0.15s;
}

.field input:focus {
  border-color: var(--accent);
}

.field input::placeholder {
  color: var(--muted);
  opacity: 0.5;
}

.field-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

/* Stripe card element box */
.card-element-box {
  background: #1c2b2f;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 13px 14px;
  transition: border-color 0.15s;
}

.card-element-box:focus-within {
  border-color: var(--accent);
}

/* Shipping summary in step 2 */
.shipping-summary {
  background: #1c2b2f;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 12px 14px;
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.summary-label {
  font-size: 0.72rem;
  color: var(--muted);
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

.summary-value {
  font-size: 0.85rem;
  color: var(--ink);
  margin: 0;
}

/* Error */
.error-msg {
  font-size: 0.82rem;
  color: #f87171;
  margin: 0;
  padding: 8px 12px;
  background: rgba(248, 113, 113, 0.08);
  border-radius: 5px;
  border: 1px solid rgba(248, 113, 113, 0.2);
}

/* Secure note */
.secure-note {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.75rem;
  color: var(--muted);
  margin: 0;
  opacity: 0.7;
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

/* Order summary sidebar */
.order-summary {
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

.order-summary h2 {
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--ink);
  margin: 0;
}

.summary-items {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.si-img {
  position: relative;
  width: 44px;
  height: 44px;
  flex-shrink: 0;
}

.si-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 5px;
  background: #111e22;
}

.si-qty {
  position: absolute;
  top: -6px;
  right: -6px;
  width: 18px;
  height: 18px;
  background: var(--muted);
  color: var(--bg);
  font-size: 0.65rem;
  font-weight: 600;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.si-info {
  flex: 1;
  min-width: 0;
}

.si-name {
  font-size: 0.82rem;
  color: var(--ink);
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.si-variant {
  font-size: 0.72rem;
  color: var(--muted);
  margin: 0;
}

.si-price {
  font-size: 0.82rem;
  color: var(--ink);
  white-space: nowrap;
}

.summary-rows {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  border-top: 1px solid var(--border);
  padding-top: 1rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.82rem;
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

/* Responsive */
@media (max-width: 768px) {
  .checkout-page {
    padding: 40px 20px;
  }
  .checkout-layout {
    grid-template-columns: 1fr;
  }
  .order-summary {
    position: static;
  }
  .field-row {
    grid-template-columns: 1fr;
  }
}
</style>
