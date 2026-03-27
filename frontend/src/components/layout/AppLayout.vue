<script setup lang="ts">
import NavBar from '@/components/NavBar.vue'
import { RouterView } from 'vue-router'
</script>

<template>
  <NavBar />
  <main>
    <RouterView />
  </main>
  <footer></footer>
</template>
