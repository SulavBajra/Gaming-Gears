import { ref } from 'vue'
import { loadStripe } from '@stripe/stripe-js'
import { useRouter } from 'vue-router'
import axiosClient from 'axios'

export function useCheckout() {
  const router = useRouter()
  const clientSecret = ref('')
  const stripe = ref<any>(null)
  const cardElement = ref<any>(null)
  const processing = ref(false)
  const error = ref('')

  async function initStripe(shippingData: Record<string, string>) {
    const { data } = await axiosClient.post('/checkout/intent', shippingData)
    clientSecret.value = data.client_secret

    stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
    const elements = stripe.value.elements()

    cardElement.value = elements.create('card', {
      style: {
        base: {
          fontFamily: 'DM Sans, sans-serif',
          fontSize: '16px',
          color: '#c8a96e', // your --accent color
        },
      },
    })

    cardElement.value.mount('#card-element')
  }

  async function submitPayment() {
    if (!stripe.value || !cardElement.value) return
    processing.value = true
    error.value = ''

    const { error: stripeError, paymentIntent } = await stripe.value.confirmCardPayment(
      clientSecret.value,
      {
        payment_method: { card: cardElement.value },
      },
    )

    if (stripeError) {
      error.value = stripeError.message ?? 'Payment failed.'
      processing.value = false
      return
    }

    // Redirect immediately — webhook handles order creation async
    router.push({
      name: 'order-success',
      query: { payment_intent: paymentIntent.id },
    })
  }

  return { initStripe, submitPayment, processing, error, cardElement }
}
