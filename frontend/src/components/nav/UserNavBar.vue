<script setup lang="ts">
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

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
      <div class="avatar">{{ user?.name?.charAt(0).toUpperCase() }}</div>
      <div class="user-info">
        <span class="greeting">Welcome back</span>
        <h2 class="user-name">{{ user?.name }}</h2>
      </div>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/profile" class="sidebar-item" active-class="active">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <circle cx="12" cy="8" r="5" />
          <path d="M20 21a8 8 0 1 0-16 0" />
        </svg>
        Profile
      </RouterLink>
      <RouterLink to="/profile/orders" class="sidebar-item" active-class="active">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <path d="M16 10a4 4 0 0 1-8 0" />
        </svg>
        Orders
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <button @click="handleLogout" class="logout-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        Logout
      </button>
    </div>
  </aside>
</template>

<style scoped>
.profile-sidebar {
  width: 240px;
  background: var(--bg);
  border-right: 1px solid var(--border);
  min-height: 100vh;
  padding: 2rem 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

/* Header */
.sidebar-header {
  display: flex;
  align-items: center;
  gap: 0.875rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border);
}

.avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: color-mix(in srgb, var(--accent) 15%, transparent);
  border: 1.5px solid var(--accent);
  color: var(--accent);
  font-family: 'Oswald', sans-serif;
  font-size: 1.1rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
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
