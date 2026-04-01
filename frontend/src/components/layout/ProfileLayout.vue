<script setup lang="ts">
import UserNavBar from '@/components/nav/UserNavBar.vue'
import NavBar from '@/components/NavBar.vue'
</script>
<template>
  <NavBar />
  <UserNavBar />
  <slot></slot>
</template>
