<script setup lang="ts">
import { useRouter, RouterLink } from 'vue-router'
const router = useRouter()
</script>

<template>
  <div class="not-found">
    <div class="inner">
      <span class="code">404</span>
      <div class="divider"></div>
      <p class="title">Page not found</p>
      <p class="sub">The page you're looking for doesn't exist or has been moved.</p>
      <div class="actions">
        <button class="btn-back" @click="router.back()">
          <i class="ti ti-arrow-left" aria-hidden="true"></i>
          Go back
        </button>
        <RouterLink to="/" class="btn-home">
          <i class="ti ti-home" aria-hidden="true"></i>
          Home
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.not-found {
  min-height: 80vh;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
  background: var(--bg);
}

.inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.875rem;
  text-align: center;
  max-width: 360px;
}

.code {
  font-family: 'Oswald', sans-serif;
  font-size: 6rem;
  font-weight: 600;
  line-height: 1;
  letter-spacing: -2px;
  color: var(--border);
}

.divider {
  width: 32px;
  height: 1px;
  background: var(--border);
}

.title {
  font-family: 'Oswald', sans-serif;
  font-size: 1.2rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: var(--ink);
  margin: 0;
}

.sub {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  color: var(--muted);
  line-height: 1.7;
  margin: 0;
}

.actions {
  display: flex;
  gap: 8px;
  margin-top: 0.5rem;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0.5rem 1.1rem;
  border-radius: 6px;
  background: transparent;
  border: 1px solid var(--border);
  color: var(--muted);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.85rem;
  cursor: pointer;
  transition:
    border-color 0.15s,
    color 0.15s;
}

.btn-back:hover {
  border-color: var(--accent);
  color: var(--ink);
}

.btn-home {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0.5rem 1.1rem;
  border-radius: 6px;
  background: var(--accent);
  color: var(--bg);
  font-family: 'Oswald', sans-serif;
  font-size: 0.85rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  text-decoration: none;
  transition: opacity 0.15s;
}

.btn-home:hover {
  opacity: 0.85;
}
</style>
