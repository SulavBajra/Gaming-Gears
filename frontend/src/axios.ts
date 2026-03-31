import axios from 'axios'
import router from './router'

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL, // e.g., http://localhost
  headers: {
    Accept: 'application/json',
  },
})

// Attach token automatically if present
axiosClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle 401 globally
axiosClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      router.push({ name: 'login' })
    }
    return Promise.reject(error)
  },
)

export default axiosClient
