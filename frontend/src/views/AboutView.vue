<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="about">
    <!-- Hero -->
    <div class="hero">
      <p class="eyebrow">Our story</p>
      <h1 class="hero-title">Built for people who <span class="muted">care about gear</span></h1>
      <p class="hero-sub">
        We started as a small team of enthusiasts who couldn't find a store that matched our
        standards. So we built one.
      </p>
    </div>

    <!-- Stats -->
    <div class="stats">
      <div class="stat">
        <span class="stat-num">2,400+</span>
        <span class="stat-label">Products listed</span>
      </div>
      <div class="stat">
        <span class="stat-num">18k</span>
        <span class="stat-label">Happy customers</span>
      </div>
      <div class="stat">
        <span class="stat-num">4.9 ★</span>
        <span class="stat-label">Average rating</span>
      </div>
    </div>

    <!-- Who we are -->
    <div class="section">
      <p class="section-label">Who we are</p>
      <p class="section-body">
        We're a Kathmandu-based team obsessed with peripherals — keyboards, mice, headsets, and
        everything in between. Every product on our shelves is hand-picked, tested, and stocked
        because we'd use it ourselves. No filler, no compromises.
      </p>
    </div>

    <!-- Values -->
    <div class="section">
      <p class="section-label">What we believe</p>
      <div class="values">
        <div class="value-card">
          <i class="ti ti-shield-check value-icon" aria-hidden="true"></i>
          <p class="value-title">Honest curation</p>
          <p class="value-desc">We only carry products we'd recommend to a friend.</p>
        </div>
        <div class="value-card">
          <i class="ti ti-bolt value-icon" aria-hidden="true"></i>
          <p class="value-title">Fast delivery</p>
          <p class="value-desc">Same-day dispatch. Most orders arrive within 2 days.</p>
        </div>
        <div class="value-card">
          <i class="ti ti-headset value-icon" aria-hidden="true"></i>
          <p class="value-title">Real support</p>
          <p class="value-desc">You talk to people who know the product.</p>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta">
      <div>
        <h3 class="cta-heading">Ready to find your next favourite peripheral?</h3>
        <p class="cta-sub">Browse our full catalogue — keyboards, mice, headsets and more.</p>
      </div>
      <RouterLink to="/shop" class="cta-btn">
        <i class="ti ti-arrow-right" aria-hidden="true"></i>
        Shop now
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css');

.about {
  max-width: 900px;
  margin: 0 auto;
  padding: 4rem 2rem;
  font-family: 'DM Sans', sans-serif;
}

/* Hero */
.hero {
  padding: 3rem 0;
  border-bottom: 1px solid var(--border);
  margin-bottom: 3rem;
}

.eyebrow {
  font-size: 0.72rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 1rem;
}

.hero-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 600;
  color: var(--ink);
  line-height: 1.15;
  max-width: 520px;
  margin: 0 0 1.25rem;
}

.hero-title .muted {
  color: var(--muted);
}

.hero-sub {
  font-size: 1rem;
  color: var(--muted);
  line-height: 1.8;
  max-width: 480px;
  margin: 0;
}

/* Stats */
.stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 3rem;
}

.stat {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 1.25rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--accent);
}

.stat-label {
  font-size: 0.8rem;
  color: var(--muted);
}

/* Sections */
.section {
  margin-bottom: 2.5rem;
}

.section-label {
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--border);
  margin-bottom: 1.25rem;
}

.section-body {
  font-size: 0.95rem;
  color: var(--muted);
  line-height: 1.8;
}

/* Values */
.values {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.value-card {
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 1.25rem;
  background: var(--bg);
}

.value-icon {
  font-size: 1.4rem;
  color: var(--accent);
  display: block;
  margin-bottom: 0.75rem;
}

.value-title {
  font-weight: 500;
  font-size: 0.9rem;
  color: var(--ink);
  margin-bottom: 6px;
}

.value-desc {
  font-size: 0.85rem;
  color: var(--muted);
  line-height: 1.6;
}

/* CTA */
.cta {
  border-top: 1px solid var(--border);
  padding-top: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.cta-heading {
  font-family: 'Oswald', sans-serif;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--ink);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 4px;
}

.cta-sub {
  font-size: 0.875rem;
  color: var(--muted);
}

.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 0.6rem 1.4rem;
  background: var(--accent);
  color: var(--bg);
  font-family: 'Oswald', sans-serif;
  font-size: 0.85rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  text-decoration: none;
  border-radius: 6px;
  transition: opacity 0.15s;
  white-space: nowrap;
}

.cta-btn:hover {
  opacity: 0.85;
}

/* Responsive */
@media (max-width: 600px) {
  .stats {
    grid-template-columns: 1fr;
  }

  .cta {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
