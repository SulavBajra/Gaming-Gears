import { ref } from 'vue'
import axios from 'axios'

const user = ref<{ id: number; name: string; email: string } | null>(null)

export function useAuth() {
  const fetchUser = async () => {
    try {
      const { data } = await axios.get('http://localhost/api/user')
      user.value = data
    } catch {
      user.value = null
    }
  }

  const logout = async () => {
    await axios.post('http://localhost/api/logout')
    user.value = null
  }

  const setUser = (userData: { id: number; name: string; email: string }, token: string) => {}

  return { user, fetchUser, logout, setUser }
}
