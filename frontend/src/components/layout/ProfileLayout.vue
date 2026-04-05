<script setup lang="ts">
import SideBar from '@/components/nav/SideBar.vue'
import NavBar from '@/components/NavBar.vue'
</script>

<template>
  <NavBar />

  <div class="profile-layout">
    <SideBar />

    <main class="profile-content">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.profile-layout {
  display: flex;
  min-height: calc(100vh - 60px); /* adjust if navbar height differs */
}

.profile-content {
  flex: 1;
  background: var(--bg-soft);
}
</style>
