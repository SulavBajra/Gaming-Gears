<script setup lang="ts">
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import { User, ShoppingBag, LogOut } from '@lucide/vue'

const { user, logout } = useAuth()
const router = useRouter()

const handleLogout = () => {
  logout()
  router.push('/')
}
</script>

<template>
  <aside class="profile-sidebar">
    <div class="sidebar-header">
      <div class="user-info">
        <span class="greeting">Welcome back</span>
        <h2 class="user-name">{{ user?.name }}</h2>
      </div>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/profile" class="sidebar-item" exact-active-class="router-link-active">
        <User />
        Profile
      </RouterLink>
      <RouterLink to="/profile/orders" class="sidebar-item" active-class="active">
        <ShoppingBag />
        Orders
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <button @click="handleLogout" class="logout-btn">
        <LogOut />
        Logout
      </button>
    </div>
  </aside>
</template>

<style scoped>
.profile-sidebar {
  width: 200px;
  background: var(--bg);
  border-right: 1px solid var(--border);
  height: 100vh;
  padding: 1.2rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 0.875rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border);
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
  min-width: 0;
}

.greeting {
  font-size: 0.7rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.user-name {
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  color: var(--ink);
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Nav */

.sidebar-item:hover,
.sidebar-item.router-link-active {
  color: var(--ink);
}

.sidebar-item:hover::after,
.sidebar-item.router-link-active::after {
  width: 100%;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  flex: 1;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.625rem 0.75rem;
  border-radius: 6px;
  color: var(--muted);
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  transition:
    background 0.15s,
    color 0.15s;
}

.sidebar-item:hover {
  background: color-mix(in srgb, var(--ink) 6%, transparent);
  color: var(--ink);
}

.sidebar-item.active {
  background: color-mix(in srgb, var(--accent) 12%, transparent);
  color: var(--accent);
}

.sidebar-item svg {
  flex-shrink: 0;
  opacity: 0.7;
}

.sidebar-item.active svg,
.sidebar-item:hover svg {
  opacity: 1;
}

/* Footer */
.sidebar-footer {
  padding-top: 1rem;
  border-top: 1px solid var(--border);
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  width: 100%;
  padding: 0.625rem 0.75rem;
  border-radius: 6px;
  background: transparent;
  border: none;
  cursor: pointer;
  color: var(--muted);
  font-size: 0.875rem;
  font-weight: 500;
  transition:
    background 0.15s,
    color 0.15s;
  text-align: left;
}

.logout-btn:hover {
  background: color-mix(in srgb, #ef4444 10%, transparent);
  color: #f87171;
}
</style>
