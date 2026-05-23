<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const { user, logout } = useAuth()
const router = useRouter()

const handleLogout = () => {
  logout()
  router.push('/')
}

const initials = computed(() => {
  if (!user.value?.name) return '?'
  return user.value.name
    .split(' ')
    .map((n) => n[0])
    .slice(0, 2)
    .join('')
    .toUpperCase()
})
</script>

<template>
  <aside class="profile-sidebar">
    <div class="sidebar-header">
      <div class="avatar">{{ initials }}</div>
      <div class="user-info">
        <span class="greeting">Welcome back</span>
        <h2 class="user-name">{{ user?.name }}</h2>
      </div>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/profile" class="sidebar-item" exact-active-class="active">
        <i class="ti ti-user" aria-hidden="true"></i>
        Profile
        <span class="active-bar"></span>
      </RouterLink>
      <RouterLink to="/profile/orders" class="sidebar-item" active-class="active">
        <i class="ti ti-shopping-bag" aria-hidden="true"></i>
        Orders
        <span class="active-bar"></span>
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <button @click="handleLogout" class="logout-btn">
        <i class="ti ti-logout" aria-hidden="true"></i>
        Log out
      </button>
    </div>
  </aside>
</template>

<style scoped>
.profile-sidebar {
  width: 210px;
  background: var(--bg);
  border-right: 1px solid var(--border);
  height: 100vh;
  padding: 1.5rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
  flex-shrink: 0;
}

/* Header */
.sidebar-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid var(--border);
}

.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: color-mix(in srgb, var(--accent) 15%, transparent);
  color: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Oswald', sans-serif;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  flex-shrink: 0;
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 1px;
  min-width: 0;
}

.greeting {
  font-size: 0.68rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.user-name {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
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
  gap: 2px;
  flex: 1;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 0.55rem 0.75rem;
  border-radius: 6px;
  color: var(--muted);
  text-decoration: none;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  font-weight: 500;
  transition:
    background 0.15s,
    color 0.15s;
  position: relative;
}

.sidebar-item i {
  font-size: 16px;
  flex-shrink: 0;
  opacity: 0.65;
  transition: opacity 0.15s;
}

.active-bar {
  display: none;
  width: 3px;
  height: 14px;
  border-radius: 2px;
  background: var(--accent);
  margin-left: auto;
  flex-shrink: 0;
}

.sidebar-item:hover {
  background: color-mix(in srgb, var(--ink) 5%, transparent);
  color: var(--ink);
}

.sidebar-item:hover i {
  opacity: 1;
}

.sidebar-item.active {
  background: color-mix(in srgb, var(--accent) 10%, transparent);
  color: var(--accent);
}

.sidebar-item.active i {
  opacity: 1;
}

.sidebar-item.active .active-bar {
  display: block;
}

/* Footer */
.sidebar-footer {
  padding-top: 1rem;
  border-top: 1px solid var(--border);
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 9px;
  width: 100%;
  padding: 0.55rem 0.75rem;
  border-radius: 6px;
  background: transparent;
  border: none;
  cursor: pointer;
  color: var(--muted);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  font-weight: 500;
  transition:
    background 0.15s,
    color 0.15s;
  text-align: left;
}

.logout-btn i {
  font-size: 16px;
  opacity: 0.65;
  transition: opacity 0.15s;
}

.logout-btn:hover {
  background: color-mix(in srgb, #ef4444 10%, transparent);
  color: #ef4444;
}

.logout-btn:hover i {
  opacity: 1;
}
</style>
