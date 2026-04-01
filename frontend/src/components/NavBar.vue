<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { ShoppingCart, Search, CircleUserRound } from '@lucide/vue'
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()
</script>

<template>
  <header class="site-header">
    <nav class="navbar">
      <div class="nav-logo">
        <RouterLink to="/" class="logo-link">Game Gears</RouterLink>
      </div>

      <ul class="nav-links">
        <li><RouterLink to="/" class="nav-item">Home</RouterLink></li>
        <li><RouterLink to="/shop" class="nav-item">Shop</RouterLink></li>
        <li><RouterLink to="/about" class="nav-item">About</RouterLink></li>
      </ul>

      <div class="nav-actions">
        <button class="icon-btn" aria-label="Search">
          <Search />
        </button>
        <button class="icon-btn" aria-label="Cart">
          <ShoppingCart />
          <span class="cart-badge">3</span>
        </button>
        <button v-if="user" class="icon-btn" aria-label="Profile">
          <RouterLink to="/profile" class="user-profile"><CircleUserRound /></RouterLink>
        </button>
        <RouterLink v-else to="/login" class="icon-btn-login" aria-label="Login">Login</RouterLink>
      </div>
    </nav>
  </header>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Iosevka+Charon:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Oswald:wght@200..700&display=swap');

.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: var(--bg);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
}

.navbar {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  height: 64px;
}

/* Logo */
.nav-logo {
  justify-self: start;
}

.logo-link {
  font-family: 'Iosevka Charon', serif;
  font-size: 1.6rem;
  font-weight: 600;
  letter-spacing: 0.2em;
  color: var(--accent);
  text-decoration: none;
  transition: color 0.2s;
}

.logo-link:hover {
  color: var(--accent);
}

/* Nav links */
.nav-links {
  display: flex;
  gap: 2.5rem;
  list-style: none;
  margin: 0;
  padding: 0;
  justify-self: center;
}

.nav-item {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.78rem;
  font-weight: 400;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  text-decoration: none;
  position: relative;
  padding-bottom: 2px;
  transition: color 0.2s;
}

.nav-item::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: var(--accent);
  transition: width 0.25s ease;
}

.nav-item:hover,
.nav-item.router-link-active {
  color: var(--ink);
}

.nav-item:hover::after,
.nav-item.router-link-active::after {
  width: 100%;
}

/* Actions */
.nav-actions {
  display: flex;
  gap: 0.5rem;
  justify-self: end;
  align-items: center;
}

.icon-btn {
  position: relative;
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  color: var(--muted);
  border-radius: 8px;
  transition:
    color 0.2s,
    background 0.2s;
  display: flex;
  align-items: center;
}

.icon-btn:hover {
  color: var(--ink);
  background: var(--border);
}

.user-profile:active,
.user-profile:visited {
  color: var(--accent);
}

.cart-badge {
  position: absolute;
  top: 4px;
  right: 4px;
  background: var(--accent);
  color: white;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.6rem;
  font-weight: 400;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-btn-login,
.icon-btn-login:active {
  text-decoration: none;
  color: var(--muted);
  padding-left: 10px;
}
</style>
