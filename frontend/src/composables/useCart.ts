import { ref, watchEffect, computed } from 'vue'
import type { CartItem, Cart, AddToCartPayload, GuestCartItem } from '@/components/types'
import axiosClient from '@/axios'
import { useAuth } from '@/composables/useAuth'
import { useToast } from 'primevue/usetoast'

const cart = ref<{
  items: (CartItem | GuestCartItem)[]
  total_items: number
  total_price: string
}>({
  items: [],
  total_items: 0,
  total_price: '0.00',
})
export function useCart() {
  const toast = useToast()
  const { user } = useAuth()

  const items = computed(() => cart.value?.items ?? [])
  const totalItems = computed(() => cart.value.total_items)

  const totalPrice = computed(() => Number(cart.value.total_price))

  const loadCart = async () => {
    if (!user.value) return
    try {
      const res = await axiosClient.get('/api/cart')
      cart.value = res.data
    } catch (e) {
      showError()
    }
  }

  const loadLocal = () => {
    const localItems: CartItem[] = JSON.parse(localStorage.getItem('cart') || '[]')
    cart.value = {
      items: localItems,
      total_items: localItems.reduce((s, i) => s + i.quantity, 0),
      total_price: String(localItems.reduce((s, i) => s + Number(i.unit_price) * i.quantity, 0)),
    }
  }

  const initCart = async () => {
    if (user.value) {
      await loadCart()
    } else {
      loadLocal()
    }
  }

  const showSuccess = (message = 'Item added to cart') => {
    toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 })
  }

  const showError = (message = 'Failed to add item to cart') => {
    toast.add({ severity: 'error', summary: 'Error', detail: message, life: 3000 })
  }

  const saveLocal = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  const add = async (
    payload: AddToCartPayload,
    meta?: { unit_price: string; product_name: string; product_variant_name: string },
  ) => {
    if (user.value) {
      try {
        const res = await axiosClient.post('/api/cart', {
          product_id: payload.product_id,
          product_variant_id: payload.product_variant_id,
          quantity: payload.quantity,
        })

        cart.value = res.data
        showSuccess()
      } catch (e: any) {
        showError(e.response?.data?.error || 'Failed to add item')
      }
      return
    }

    // ───── Guest user ─────
    const existing = cart.value.items.find(
      (i) =>
        i.product_id === payload.product_id && i.product_variant_id === payload.product_variant_id,
    )

    if (existing) {
      existing.quantity += payload.quantity
      existing.item_total_price = String(Number(existing.unit_price) * existing.quantity)
    } else {
      cart.value.items.push({
        cart_item_id: Date.now(),
        cart_id: 0,
        product_id: payload.product_id,
        product_variant_id: payload.product_variant_id,
        quantity: payload.quantity,
        unit_price: meta!.unit_price,
        updated_at: new Date().toISOString(),
        item_total_price: String(Number(meta!.unit_price) * payload.quantity),
        product_name: meta!.product_name,
        product_variant_name: meta!.product_variant_name,
      })
    }

    recomputeTotals()
    saveLocal()
    showSuccess()
  }

  const recomputeTotals = () => {
    if (!cart.value) return
    cart.value.total_items = cart.value.items.reduce((s, i) => s + i.quantity, 0)
    cart.value.total_price = String(
      cart.value.items.reduce((s, i) => s + Number(i.unit_price) * i.quantity, 0),
    )
  }

  const remove = (item: CartItem) => {
    if (!cart.value) return

    cart.value.items = cart.value.items.filter(
      (i) =>
        !(i.product_id === item.product_id && i.product_variant_id === item.product_variant_id),
    )

    recomputeTotals()
    saveLocal()
  }

  const updateQuantity = (item: CartItem, quantity: number) => {
    if (!cart.value) return

    const existing = cart.value.items.find(
      (i) => i.product_id === item.product_id && i.product_variant_id === item.product_variant_id,
    )

    if (existing) {
      existing.quantity = quantity
      existing.item_total_price = String(Number(existing.unit_price) * quantity) // keep consistent

      recomputeTotals()
      saveLocal()
    }
  }

  const clear = () => {
    cart.value = { items: [], total_items: 0, total_price: '0.00' }
    saveLocal()
  }
  const syncWithServer = async () => {
    if (!user.value) return
    const localCart = JSON.parse(localStorage.getItem('cart') || '[]')
    if (!localCart.length) return

    try {
      const res = await axiosClient.post('/api/cart/bulk', { items: localCart })
      cart.value = res.data.items
      localStorage.removeItem('cart')
    } catch (e) {
      console.error('Cart sync failed', e)
    }
  }
  return {
    cart,
    totalItems,
    initCart,
    totalPrice,
    loadLocal,
    saveLocal,
    loadCart,
    add,
    remove,
    updateQuantity,
    clear,
    syncWithServer,
  }
}
