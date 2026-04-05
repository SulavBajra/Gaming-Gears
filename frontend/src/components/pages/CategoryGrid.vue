<script setup lang="ts">
import keyboardImg from '@/assets/category/keyboard.jpg'
import mouseImg from '@/assets/category/mouse.jpg'
import headsetImg from '@/assets/category/headset.jpg'

const categories = [
  {
    name: 'Gaming Keyboards',
    slug: 'keyboards',
    icon: keyboardImg,
  },
  {
    name: 'GamingMice',
    slug: 'mice',
    icon: mouseImg,
  },
  {
    name: 'Gaming Headsets',
    slug: 'headsets',
    icon: headsetImg,
  },
  {
    name: 'Gaming Accessories',
    slug: 'accessories',
    icon: headsetImg,
  },
]
</script>

<template>
  <section class="grid-section">
    <div class="grid">
      <RouterLink
        v-for="cat in categories"
        :key="cat.slug"
        :to="{ path: '/shop', query: { category: cat.slug } }"
        class="card"
      >
        <div class="icon"><img :src="cat.icon" alt="{{ cat.name }}" /></div>
        <h3 class="name">{{ cat.name }}</h3>
      </RouterLink>
    </div>
  </section>
</template>

<style scoped>
.grid-section {
  padding: 2.5rem 2rem;
}

.grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 2rem;
}

.card {
  background: #2e4248;
  border: 1px solid #ffffff12;
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  text-decoration: none;
  transition: 0.25s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
}

img {
  width: 48px;
}

.card:hover {
  transform: translateY(-6px);
  background: #36545b;
  border-color: #ffffff25;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.card::after {
  content: '';
  width: 40px;
  height: 2px;
  background: #c8a96e;
  margin-top: 6px;
}

.icon {
  font-size: 2rem;
}

.name {
  font-family: 'Oswald', sans-serif;
  font-size: 1rem;
  color: #f5f7f8;
  letter-spacing: 0.08em;
}
</style>
