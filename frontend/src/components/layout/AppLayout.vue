<script setup lang="ts">
import NavBar from '@/components/NavBar.vue'
import Footer from '@/components/pages/Footer.vue'

import { RouterView } from 'vue-router'
</script>

<template>
  <NavBar />
  <main>
    <RouterView />
  </main>
  <footer><Footer /></footer>
</template>
