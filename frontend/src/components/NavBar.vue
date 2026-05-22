<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { ShoppingCart, Heart, CircleUserRound, House, Store, Menu, X } from '@lucide/vue'
import { useAuth } from '@/composables/useAuth'
import { useCart } from '@/composables/useCart'

const { user } = useAuth()
const { itemCount, fetchCart } = useCart()

const menuOpen = ref(false)

function toggleMenu() {
  menuOpen.value = !menuOpen.value
}

function closeMenu() {
  menuOpen.value = false
}

function showInfo(msg: string) {
  console.info(msg)
}

onMounted(() => {
  fetchCart()
})
</script>

<template>
  <header class="site-header">
    <nav class="navbar">
      <!-- Logo -->
      <div class="nav-logo">
        <RouterLink to="/" class="logo-link" @click="closeMenu">Game Gears</RouterLink>
      </div>

      <!-- Desktop nav links -->
      <ul class="nav-links">
        <li>
          <RouterLink to="/" class="nav-item" exact-active-class="router-link-active">
            <House class="house-icon" />Home
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/shop" class="nav-item"> <Store class="house-icon" />Shop </RouterLink>
        </li>
        <li><RouterLink to="/about" class="nav-item">About</RouterLink></li>
      </ul>

      <!-- Desktop actions -->
      <div class="nav-actions">
        <RouterLink v-if="user" to="/wishlist" class="icon-btn" aria-label="Wishlist">
          <Heart />
        </RouterLink>
        <button
          v-else
          class="icon-btn"
          aria-label="Wishlist"
          @click="showInfo('Login to use wishlist')"
        >
          <Heart />
        </button>

        <RouterLink to="/cart" class="icon-btn" aria-label="Cart">
          <ShoppingCart />
          <span class="cart-badge">{{ itemCount }}</span>
        </RouterLink>

        <button v-if="user" class="icon-btn" aria-label="Profile">
          <RouterLink to="/profile" class="user-profile"><CircleUserRound /></RouterLink>
        </button>
        <RouterLink v-else to="/login" class="icon-btn-login" aria-label="Login">Login</RouterLink>
      </div>

      <!-- Mobile right side: cart + hamburger -->
      <div class="mobile-actions">
        <RouterLink to="/cart" class="icon-btn" aria-label="Cart">
          <ShoppingCart />
          <span class="cart-badge">{{ itemCount }}</span>
        </RouterLink>

        <button
          class="icon-btn hamburger"
          :aria-expanded="menuOpen"
          aria-label="Toggle menu"
          @click="toggleMenu"
        >
          <X v-if="menuOpen" />
          <Menu v-else />
        </button>
      </div>
    </nav>

    <!-- Mobile drawer -->
    <Transition name="drawer">
      <div v-if="menuOpen" class="mobile-drawer">
        <ul class="mobile-nav-links">
          <li>
            <RouterLink
              to="/"
              class="mobile-nav-item"
              exact-active-class="router-link-active"
              @click="closeMenu"
            >
              <House class="house-icon" />Home
            </RouterLink>
          </li>
          <li>
            <RouterLink to="/shop" class="mobile-nav-item" @click="closeMenu">
              <Store class="house-icon" />Shop
            </RouterLink>
          </li>
          <li>
            <RouterLink to="/about" class="mobile-nav-item" @click="closeMenu">About</RouterLink>
          </li>
        </ul>

        <div class="mobile-drawer-footer">
          <RouterLink v-if="user" to="/wishlist" class="mobile-nav-item" @click="closeMenu">
            <Heart class="house-icon" />Wishlist
          </RouterLink>
          <button
            v-else
            class="mobile-nav-item ghost-btn"
            @click="
              () => {
                showInfo('Login to use wishlist')
                closeMenu()
              }
            "
          >
            <Heart class="house-icon" />Wishlist
          </button>

          <RouterLink v-if="user" to="/profile" class="mobile-nav-item" @click="closeMenu">
            <CircleUserRound class="house-icon" />Profile
          </RouterLink>
          <RouterLink v-else to="/login" class="mobile-nav-item accent" @click="closeMenu">
            Login
          </RouterLink>
        </div>
      </div>
    </Transition>
  </header>
</template>

<style scoped>
.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: var(--dark-bg);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  font-family: 'Iosevka Charon', monospace;
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

/* Desktop nav links */
.nav-links {
  display: flex;
  gap: 2.5rem;
  list-style: none;
  margin: 0;
  padding: 0;
  justify-self: center;
}

.nav-item {
  font-family: 'Iosevka Charon', monospace;
  font-size: 0.88rem;
  font-weight: 400;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  text-decoration: none;
  position: relative;
  padding-bottom: 2px;
  transition: color 0.2s;
}

.house-icon {
  height: 0.78rem;
  flex-shrink: 0;
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

/* Desktop actions */
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

.user-profile {
  color: inherit;
  display: flex;
  align-items: center;
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

/* Mobile controls — hidden on desktop */
.mobile-actions {
  display: none;
  justify-self: end;
  align-items: center;
  gap: 0.25rem;
}

.hamburger {
  padding: 8px;
}

/* Mobile drawer */
.mobile-drawer {
  border-top: 1px solid var(--border);
  background: var(--dark-bg);
  padding: 1rem 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.mobile-nav-links {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.mobile-nav-item {
  font-family: 'Iosevka Charon', monospace;
  font-size: 0.92rem;
  font-weight: 400;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  text-decoration: none;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 8px;
  transition: color 0.2s;
  width: 100%;
}

.mobile-nav-item:hover,
.mobile-nav-item.router-link-active {
  color: var(--ink);
}

.mobile-nav-item.accent {
  color: var(--accent);
}

.mobile-drawer-footer {
  display: flex;
  flex-direction: column;
  margin-top: 0.25rem;
}

.ghost-btn {
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
}

/* Drawer transition */
.drawer-enter-active,
.drawer-leave-active {
  transition:
    opacity 0.2s ease,
    transform 0.2s ease;
}

.drawer-enter-from,
.drawer-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

/* Responsive breakpoint */
@media (max-width: 768px) {
  .navbar {
    grid-template-columns: 1fr auto;
    padding: 0 1.25rem;
  }

  .nav-links,
  .nav-actions {
    display: none;
  }

  .mobile-actions {
    display: flex;
  }
}
</style>
