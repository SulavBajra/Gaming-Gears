import { ref } from 'vue'
import axiosClient from '@/axios'
import type { User } from '@/components/types'

const user = ref<User>(null)

export function useAuth() {
  const fetchUser = async () => {
    try {
      if (!localStorage.getItem('token')) {
        user.value = null
        return
      }
      const { data } = await axiosClient.get('/api/user')
      user.value = data
    } catch {
      user.value = null
    }
  }

  const logout = async () => {
    try {
      await axiosClient.post('/api/logout') // POST is more conventional for logout
      localStorage.removeItem('token')
      user.value = null
    } catch (error) {
      console.error('Logout failed:', error)
    }
  }

  return { user, fetchUser, logout }
}
