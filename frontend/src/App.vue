<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const { fetchUser } = useAuth()
fetchUser()
</script>

<template>
  <div class="base-view">
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
