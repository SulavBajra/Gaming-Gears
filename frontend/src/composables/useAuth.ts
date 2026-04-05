import { ref } from 'vue'
import axiosClient from '@/axios'
import type { User } from '@/components/types'
import type { Profile } from '@/components/types'

const user = ref<User>(null)
const profile = ref<Profile>()

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

  const getUserProfile = async () => {
    try {
      const response = await axiosClient.get('/api/profile')
      profile.value = response.data.data
    } catch (error) {
      console.error('Get user profile failed:', error)
      return null
    }
  }

  return { user, fetchUser, logout, profile, getUserProfile }
}
