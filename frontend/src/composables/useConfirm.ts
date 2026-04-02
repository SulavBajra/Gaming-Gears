import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import { useCart } from '@/composables/useCart'

export function useConfirmDialog() {
  const confirm = useConfirm()
  const toast = useToast()
  const { removeItem, clearCart } = useCart()

  const deleteCartItem = (id: number) => {
    confirm.require({
      message: 'Do you want to delete this Item?',
      header: 'Delete Item',
      rejectLabel: 'Cancel',
      rejectProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true,
      },
      acceptProps: {
        label: 'Delete',
        severity: 'danger',
      },
      accept: () => {
        removeItem(id)
      },
      reject: () => {
        toast.add({
          severity: 'error',
          summary: 'Rejected',
          detail: 'You have rejected',
          life: 3000,
        })
      },
    })
  }

  const clearEntireCart = (cartId: number) => {
    confirm.require({
      message: 'Do you want to clear the cart?',
      header: 'Clear Cart',
      rejectLabel: 'Cancel',
      rejectProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true,
      },
      acceptProps: {
        label: 'Clear',
        severity: 'danger',
      },
      accept: () => {
        clearCart(cartId)
      },
      reject: () => {
        toast.add({
          severity: 'error',
          summary: 'Rejected',
          detail: 'You have rejected',
          life: 3000,
        })
      },
    })
  }

  return { deleteCartItem, clearEntireCart }
}
