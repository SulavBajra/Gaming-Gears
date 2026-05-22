import { computed, ref } from 'vue'
import axiosClient from '@/axios'
import { useAuth } from '@/composables/useAuth'
import { useToaster } from './useToast'

export interface CartItem {
  id: number
  product_id: number
  product_variant_id: number | null
  quantity: number
  unit_price: number
  item_total_price: number
  updated_at: string
  product_name: string | null
  product_variant_name: string | null
  thumbnail: string | null
}

export interface Cart {
  id: number | null
  expires_at: string | null
  total_items: number
  total_price: number
  items: CartItem[]
}

const cart = ref<Cart | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

export function useCart() {
  const { showError, showSuccess } = useToaster()
  const { user } = useAuth()
  const items = computed(() => cart.value?.items ?? [])
  const itemCount = computed(() => items.value.reduce((sum, item) => sum + item.quantity, 0))
  const subtotal = computed(() => items.value.reduce((sum, item) => sum + item.item_total_price, 0))
  const isEmpty = computed(() => items.value.length === 0)
  const totalItems = computed(() => items.value.reduce((sum, item) => sum + item.quantity, 0))
  const totalPrice = computed(() => cart.value?.total_price ?? 0)

  const fetchCart = async () => {
    if (!user.value) {
      const pending = sessionStorage.getItem('pendingCart')
      if (!pending) {
        cart.value = null
        return
      }
      const cartItems: any[] = JSON.parse(pending)
      if (!cartItems.length) {
        cart.value = null
        return
      }
      const totalPrice = cartItems.reduce((sum, i) => sum + i.unit_price * i.quantity, 0)
      const totalQty = cartItems.reduce((sum, i) => sum + i.quantity, 0)

      cart.value = {
        id: null,
        expires_at: null,
        total_items: totalQty,
        total_price: totalPrice,
        items: cartItems.map((i, index) => ({
          id: index, // use index as fake id for keying
          product_id: i.product_id,
          product_variant_id: i.product_variant_id,
          quantity: i.quantity,
          unit_price: i.unit_price,
          item_total_price: i.unit_price * i.quantity,
          updated_at: '',
          product_name: i.product_name,
          product_variant_name: i.product_variant_name,
          thumbnail: i.thumbnail,
        })),
      }
      return
    }
    // authenticated path
    try {
      const { data } = await axiosClient.get('/api/cart')
      cart.value = data.data
    } catch {
      cart.value = null
    }
  }

  const addToCart = async (productId: number, variantId: number | null = null, quantity = 1) => {
    loading.value = true
    error.value = null
    try {
      const { data } = await axiosClient.post('/api/cart', {
        product_id: productId,
        product_variant_id: variantId,
        quantity,
      })
      cart.value = data.data
      return true
    } catch {
      error.value = 'Could not add item to cart.'
      return false
    } finally {
      loading.value = false
    }
  }

  const handleGuestAddToCart = async (
    productId: number,
    variantId: number | null = null,
    quantity = 1,
    productSlug: string,
    productName: string,
    variantName: string | null,
    unitPrice: number,
    thumbnail: string | null,
  ) => {
    const existing = sessionStorage.getItem('pendingCart')
    const cartItems: any[] = existing ? JSON.parse(existing) : []

    const index = cartItems.findIndex(
      (i) => i.product_id === productId && i.product_variant_id === variantId,
    )

    if (index !== -1) {
      cartItems[index].quantity += quantity
    } else {
      cartItems.push({
        product_id: productId,
        product_variant_id: variantId,
        quantity,
        slug: productSlug,
        product_name: productName,
        product_variant_name: variantName,
        unit_price: unitPrice,
        thumbnail,
      })
    }

    sessionStorage.setItem('pendingCart', JSON.stringify(cartItems))
    await fetchCart()
  }

  const restorePendingCart = async () => {
    const pending = sessionStorage.getItem('pendingCart')
    if (!pending) return
    const cartItems: any[] = JSON.parse(pending)
    for (const item of cartItems) {
      await addToCart(item.product_id, item.product_variant_id, item.quantity)
    }
    sessionStorage.removeItem('pendingCart')
  }

  const updateQuantity = async (cartItemId: number, quantity: number) => {
    if (!user.value) {
      const pending = sessionStorage.getItem('pendingCart')
      if (!pending) return
      const cartItems: any[] = JSON.parse(pending)
      if (quantity < 1) {
        cartItems.splice(cartItemId, 1)
      } else {
        cartItems[cartItemId].quantity = quantity
      }
      sessionStorage.setItem('pendingCart', JSON.stringify(cartItems))
      await fetchCart()
      return
    }
    if (quantity < 1) return removeItem(cartItemId)
    loading.value = true
    error.value = null
    try {
      const { data } = await axiosClient.patch(`/api/cart/items/${cartItemId}`, { quantity })
      cart.value = data.data
      fetchCart()
    } catch {
      error.value = 'Could not update quantity.'
      showError(error.value)
    } finally {
      loading.value = false
    }
  }

  const removeItem = async (cartItemId: number) => {
    if (!user.value) {
      const pending = sessionStorage.getItem('pendingCart')
      if (!pending) return
      const cartItems: any[] = JSON.parse(pending)
      cartItems.splice(cartItemId, 1)
      sessionStorage.setItem('pendingCart', JSON.stringify(cartItems))
      await fetchCart()
      return
    }
    loading.value = true
    error.value = null
    try {
      const { data } = await axiosClient.delete(`/api/cart/items/${cartItemId}`)
      cart.value = data.data
      const message = data.message
      if (message) showSuccess(message)
      fetchCart()
    } catch {
      error.value = 'Could not remove item.'
      showError(error.value)
    } finally {
      loading.value = false
    }
  }

  const clearCart = async (cartId: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await axiosClient.delete(`/api/cart/${cartId}`)
      cart.value = null
      showSuccess(response.data.message)
    } catch {
      error.value = 'Could not clear cart.'
    } finally {
      loading.value = false
    }
  }

  const isInCart = (productId: number, variantId: number | null = null) =>
    items.value.some(
      (item) => item.product_id === productId && item.product_variant_id === variantId,
    )

  const getCartItem = (productId: number, variantId: number | null = null) =>
    items.value.find(
      (item) => item.product_id === productId && item.product_variant_id === variantId,
    ) ?? null

  return {
    cart,
    loading,
    error,
    items,
    itemCount,
    handleGuestAddToCart,
    restorePendingCart,
    subtotal,
    isEmpty,
    fetchCart,
    addToCart,
    updateQuantity,
    removeItem,
    clearCart,
    isInCart,
    getCartItem,
    totalItems,
    totalPrice,
  }
}
