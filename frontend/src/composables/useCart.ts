import { ref } from 'vue'
import type { CartItem } from '@/components/types'
import axiosClient from '@/axios'
import { useAuth } from '@/composables/useAuth'
import { useToast } from 'primevue/usetoast'

const cart = ref<CartItem[]>([])

export function useCart() {
  const toast = useToast()
  const { user } = useAuth()

  const loadCart = async () => {
    if (!user.value) return
    try {
      const res = await axiosClient.get('/api/cart')
      cart.value = res.data.items
    } catch (e) {
      console.error('Failed to load cart', e)
    }
  }

  const showSuccess = (message = 'Item added to cart') => {
    toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 })
  }

  const loadLocal = () => {
    cart.value = JSON.parse(localStorage.getItem('cart') || '[]')
  }

  const saveLocal = () => {
    localStorage.setItem('cart', JSON.stringify(cart.value))
  }

  const add = async (item: CartItem) => {
    if (!user.value) {
      // Guest cart
      const existing = cart.value.find(
        (i) => i.product_id === item.product_id && i.product_variant_id === item.product_variant_id,
      )

      if (existing) {
        existing.quantity += item.quantity
      } else {
        cart.value.push(item)
      }

      saveLocal()
      showSuccess()
      return
    }

    // Logged-in cart
    try {
      const res = await axiosClient.post('/api/cart', item)
      cart.value = res.data.items // Use backend response
      showSuccess()
    } catch (e: any) {
      console.error('Add to cart failed', e)
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: e.response?.data?.error || 'Failed to add item',
        life: 3000,
      })
    }
  }

  const remove = (item: CartItem) => {
    cart.value = cart.value.filter(
      (i) =>
        !(i.product_id === item.product_id && i.product_variant_id === item.product_variant_id),
    )
    saveLocal()
  }

  const updateQuantity = (item: CartItem, quantity: number) => {
    const existing = cart.value.find(
      (i) => i.product_id === item.product_id && i.product_variant_id === item.product_variant_id,
    )

    if (existing) {
      existing.quantity = quantity
      saveLocal()
    }
  }

  const clear = () => {
    cart.value = []
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
