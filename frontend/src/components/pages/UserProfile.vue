<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const { logout, user } = useAuth()
const router = useRouter()
const open = ref(false)

const handleLogout = () => {
  logout()
  router.push('/')
}
</script>

<template>
  <p>adasd</p>
</template>

<style scoped>
.user-profile-wrapper {
  position: relative;
}

.user-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: #1a272b;
  border: 1px solid rgba(255, 255, 255, 0.07);
  border-radius: 8px;
  padding: 0.5rem 0;
  min-width: 180px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 100;
}

.user-dropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.user-dropdown li {
  padding: 0.5rem 1rem;
}

.user-dropdown li button,
.user-dropdown li a {
  width: 100%;
  text-align: left;
  background: none;
  border: none;
  color: #f0ece4;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
}

.user-dropdown li button:hover,
.user-dropdown li a:hover {
  background: rgba(255, 255, 255, 0.05);
}
</style>
