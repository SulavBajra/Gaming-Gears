import { useToast } from 'primevue/usetoast'

export function useToaster() {
  const toast = useToast()

  const showSuccess = (message = 'Item added to cart') => {
    toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 })
  }

  const showError = (message = 'Failed to add item to cart') => {
    toast.add({ severity: 'error', summary: 'Error', detail: message, life: 3000 })
  }

  return { showSuccess, showError }
}
