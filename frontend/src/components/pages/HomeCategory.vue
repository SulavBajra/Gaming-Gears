<template>
  <section class="p-16">
    <CategoryGrid v-if="categories.length" :categories="categories" />
  </section>
</template>
<style scoped>
.recent-products {
  background-color: var(--bg);
}
</style>
