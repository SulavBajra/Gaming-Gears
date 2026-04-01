<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import { useCart } from '@/composables/useCart'

const { fetchUser } = useAuth()
const { initCart } = useCart()

onMounted(async () => {
  await fetchUser()
  await initCart()
})
</script>

<template>
  <div class="base-view">
    <Toast />
    <RouterView />
  </div>
</template>

<style>
* {
  box-sizing: border-box;
  padding: 0;
  margin: 0;
}

:root {
  --ink: #ffff;
  --muted: #8a8a8a;
  --accent: #c8a96e;
  --bg: #253439;
  --border: #e8e4df;
}

.base-view {
  background-color: var(--bg);
}
</style>
