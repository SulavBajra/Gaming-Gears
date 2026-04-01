import { ref, watchEffect, computed } from 'vue'
import type { CartItem, Cart } from '@/components/types'
import axiosClient from '@/axios'
import { useAuth } from '@/composables/useAuth'
import { useToast } from 'primevue/usetoast'

const cart = ref<Cart | null>(null)

export function useCart() {
  const toast = useToast()
  const { user } = useAuth()

  const items = computed(() => cart.value?.items ?? [])
  const totalItems = computed(() => {
    return cart.value?.total_items ?? items.value.reduce((sum, i) => sum + i.quantity, 0)
  })

  const totalPrice = computed(() => {
    const server = cart.value?.total_price
    if (server != null) return Number(server)
    return items.value.reduce((sum, i) => sum + Number(i.unit_price) * i.quantity, 0)
  })

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

  watchEffect(() => {
    if (user.value) {
      loadCart()
    } else {
      loadLocal()
    }
  })

  const showSuccess = (message = 'Item added to cart') => {
    toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 })
  }

  const showError = (message = 'Failed to add item to cart') => {
    toast.add({ severity: 'error', summary: 'Error', detail: message, life: 3000 })
  }

  const saveLocal = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  const add = async (item: CartItem) => {
    if (!user.value) {
      if (!cart.value) {
        cart.value = { items: [], total_items: 0, total_price: '0.00' }
      }

      const existing = cart.value.items.find(
        (i) => i.product_id === item.product_id && i.product_variant_id === item.product_variant_id,
      )

      if (existing) {
        existing.quantity += item.quantity
        existing.item_total_price = String(Number(existing.unit_price) * existing.quantity)
      } else {
        cart.value.items.push(item)

        saveLocal()
        showSuccess()
        return
      }

      try {
        const res = await axiosClient.post('/api/cart', item)
        cart.value = res.data.items
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
