<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="wrapper">
    <nav class="text-black">
      <RouterLink to="/">Home</RouterLink>
      <RouterLink to="/shop">Products</RouterLink>
    </nav>
  </div>
  <RouterView />
</template>
<style scoped>
.wrapper {
  padding: 5px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
nav {
  display: flex;
  flex-wrap: row;
  align-items: center;
  justify-content: space-around;
  width: 50%;
  gap: 1rem;
  padding: 10px;
  border: 1px solid beige;
  border-radius: 20px;
}

nav a {
  text-decoration: none;
  color: black;
}

nav a:hover {
  color: #42b983;
}
</style>
