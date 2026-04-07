<script setup lang="ts">
const shopLinks = [
  { name: 'All Products', to: '/shop' },
  { name: 'Keyboards', to: '/shop?category=keyboards' },
  { name: 'Mice', to: '/shop?category=mice' },
  { name: 'Headsets', to: '/shop?category=headsets' },
]

const accountLinks = [
  { name: 'Login', to: '/login' },
  { name: 'Register', to: '/register' },
  { name: 'Cart', to: '/cart' },
]

const supportLinks = [
  { name: 'Contact', to: '/contact' },
  { name: 'FAQ', to: '/faq' },
]

const socials = ['X', 'in', 'GH']
</script>

<template>
  <footer>
    <div class="footer-inner">
      <!-- Company -->
      <div class="company-section">
        <div class="logo-mark">
          <span class="logo-icon">◈</span>
          <span class="logo-name">Game Gears</span>
        </div>

        <p class="tagline">Premium gaming gear for competitive performance.</p>

        <div class="socials">
          <a v-for="s in socials" :key="s" href="#" class="social-link">
            {{ s }}
          </a>
        </div>
      </div>

      <!-- Links -->
      <div class="links">
        <div class="link-col">
          <h4>Shop</h4>
          <ul>
            <li v-for="l in shopLinks" :key="l.name">
              <RouterLink :to="l.to">{{ l.name }}</RouterLink>
            </li>
          </ul>
        </div>

        <div class="link-col">
          <h4>Account</h4>
          <ul>
            <li v-for="l in accountLinks" :key="l.name">
              <RouterLink :to="l.to">{{ l.name }}</RouterLink>
            </li>
          </ul>
        </div>

        <div class="link-col">
          <h4>Support</h4>
          <ul>
            <li v-for="l in supportLinks" :key="l.name">
              <RouterLink :to="l.to">{{ l.name }}</RouterLink>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom -->
    <div class="footer-bottom">
      <span>Built with ♥ in Kathmandu</span>
    </div>
  </footer>
</template>
<style>
footer {
  background-color: var(--dark-bg);
  font-family: 'DM Sans', sans-serif;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  /*padding: 20px 0 0;*/
  color: var(--text, #e8e4dc);
}

.footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 0 0 40px;
  display: grid;
  grid-template-columns: 1.5fr 2fr;
  gap: 48px;
}

/* ── Company Section ── */
.company-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.logo-mark {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo-icon {
  font-size: 1.6rem;
  color: var(--accent, #c9a96e);
  line-height: 1;
}

.logo-name {
  font-family: 'DM Serif Display', serif;
  font-size: 1.5rem;
  letter-spacing: 0.01em;
  color: var(--text, #e8e4dc);
}

.tagline {
  font-size: 0.875rem;
  font-weight: 300;
  color: var(--muted, rgba(232, 228, 220, 0.5));
  line-height: 1.6;
  max-width: 260px;
  margin: 0;
}

.socials {
  display: flex;
  gap: 12px;
  margin-top: 4px;
}

.social-link {
  display: grid;
  place-items: center;
  width: 36px;
  height: 36px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 8px;
  color: var(--muted, rgba(232, 228, 220, 0.55));
  font-size: 0.85rem;
  text-decoration: none;
  transition:
    border-color 0.2s,
    color 0.2s,
    background 0.2s;
}

.social-link:hover {
  border-color: var(--accent, #c9a96e);
  color: var(--accent, #c9a96e);
  background: rgba(201, 169, 110, 0.06);
  transform: translateY(-2px);
}

/* ── Links Group ── */
.links {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}

.link-col h4 {
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--accent, #c9a96e);
  margin: 0 0 20px;
}

.link-col ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.link-col ul li a {
  font-size: 0.875rem;
  font-weight: 300;
  color: var(--muted, rgba(232, 228, 220, 0.55));
  text-decoration: none;
  transition: color 0.18s;
}

.link-col ul li a:hover {
  color: var(--text, #e8e4dc);
}

/* ── Bottom Bar ── */
.footer-bottom {
  max-width: 1200px;
  margin: 48px auto 0;
  padding: 20px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  font-size: 0.78rem;
  color: var(--muted, rgba(232, 228, 220, 0.35));
}

.built-with {
  letter-spacing: 0.02em;
}

.link-col ul li a {
  position: relative;
}

.link-col ul li a::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -2px;
  width: 0;
  height: 1px;
  background: var(--accent);
  transition: width 0.2s;
}

.link-col ul li a:hover::after {
  width: 100%;
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .footer-inner {
    grid-template-columns: 1fr 1fr;
    gap: 36px;
  }

  .company-section {
    grid-column: 1 / -1;
  }

  .links-group {
    display: contents;
  }

  .footer-bottom {
    flex-direction: column;
    gap: 8px;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .footer-inner {
    grid-template-columns: 1fr;
    padding: 0 24px;
  }

  .footer-bottom {
    padding: 20px 24px;
  }
}
</style>
