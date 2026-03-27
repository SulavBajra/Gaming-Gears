<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>
<template>
  <section class="hero">
    <div class="hero-inner">
      <!-- Left -->
      <div class="hero-left">
        <span class="hero-tag">Premium Gaming Gear</span>
        <h1 class="hero-headline">
          GEAR UP<br />
          <span class="line-accent">for victory</span>
          PLAY HARD
        </h1>
        <p class="hero-sub">
          Precision-engineered peripherals for players who demand more. Every millisecond. Every
          click. Every win.
        </p>
        <div class="hero-actions">
          <RouterLink to="/shop" class="btn-primary">Shop Now</RouterLink>
          <RouterLink to="/shop" class="btn-ghost">Browse Collection</RouterLink>
        </div>
        <div class="hero-stats">
          <div class="stat">
            <div class="stat-num">12K<span>+</span></div>
            <div class="stat-label">Products</div>
          </div>
          <div class="stat">
            <div class="stat-num">98<span>%</span></div>
            <div class="stat-label">Satisfaction</div>
          </div>
          <div class="stat">
            <div class="stat-num">4.9<span>★</span></div>
            <div class="stat-label">Avg. Rating</div>
          </div>
        </div>
      </div>
      <!-- Right -->
      <div class="hero-right">
        <div class="product-stage">
          <!-- Rings -->
          <div class="ring ring-1"></div>
          <div class="ring ring-2"></div>
          <div class="ring ring-3"></div>

          <!-- Main Product -->
          <img class="product-img" src="../../assets/keyboard.svg" alt="Keyboard" />

          <!-- Floating stats -->
          <div class="float-card fc-1">
            <div class="fc-label">Latency</div>
            <div class="fc-val fc-accent">1ms</div>
          </div>

          <div class="float-card fc-2">
            <div class="fc-label">Switch</div>
            <div class="fc-val">Optical</div>
          </div>

          <div class="float-card fc-3">
            <div class="fc-label">RGB</div>
            <div class="fc-val fc-accent">Yes</div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<style scoped>
.hero {
  position: relative;
  background: var(--bg);
  min-height: 88vh;
  display: flex;
  align-items: center;
  overflow: hidden;
}

/* grid overlay */
.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(#ffffff08 1px, transparent 1px),
    linear-gradient(90deg, #ffffff08 1px, transparent 1px);
  background-size: 80px 80px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
}

/* accent glow */
.hero::after {
  content: '';
  position: absolute;
  top: -10%;
  right: -5%;
  width: 55%;
  height: 100%;
  background: radial-gradient(ellipse 60% 70% at 70% 40%, #c8a96e18 0%, transparent 65%);
  pointer-events: none;
}

.hero-inner {
  position: relative;
  z-index: 2;
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  padding: 6rem 3rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 5rem;
  align-items: center;
}

/* LEFT */
.hero-left {
  display: flex;
  flex-direction: column;
}

/* entrance animation */
.hero-left > * {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeUp 0.6s ease forwards;
}
.hero-left > *:nth-child(1) {
  animation-delay: 0.1s;
}
.hero-left > *:nth-child(2) {
  animation-delay: 0.2s;
}
.hero-left > *:nth-child(3) {
  animation-delay: 0.3s;
}
.hero-left > *:nth-child(4) {
  animation-delay: 0.4s;
}
.hero-left > *:nth-child(5) {
  animation-delay: 0.5s;
}

.hero-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.7rem;
  font-weight: 300;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--accent);
  margin-bottom: 1.6rem;
}
.hero-tag::before {
  content: '';
  width: 24px;
  height: 1px;
  background: var(--accent);
}

.hero-headline {
  font-family: 'Oswald', sans-serif;
  font-size: clamp(3rem, 5.5vw, 5.5rem);
  font-weight: 700;
  line-height: 0.92;
  letter-spacing: -0.02em;
  color: var(--ink);
}
.hero-headline .line-accent {
  color: var(--accent);
  font-weight: 200;
  font-style: italic;
  font-size: 0.78em;
  display: block;
}

.hero-sub {
  font-family: 'DM Sans', sans-serif;
  font-size: 1rem;
  font-weight: 300;
  line-height: 1.7;
  color: var(--muted);
  max-width: 400px;
  margin: 1.8rem 0 2.8rem;
}

/* buttons */
.hero-actions {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.btn-primary {
  font-family: 'Oswald', sans-serif;
  font-size: 0.8rem;
  font-weight: 600;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  padding: 0.9rem 2.2rem;
  background: var(--accent);
  color: #1a1a1a;
  text-decoration: none;
  clip-path: polygon(
    0 0,
    calc(100% - 10px) 0,
    100% 10px,
    100% 100%,
    10px 100%,
    0 calc(100% - 10px)
  );
  box-shadow: 0 12px 30px rgba(200, 169, 110, 0.2);
  transition: 0.2s;
}
.btn-primary:hover {
  transform: translateY(-2px);
  background: var(--accent2, #a8845a);
}
.btn-primary:active {
  transform: translateY(0);
}

.btn-ghost {
  font-family: 'DM Sans', sans-serif;
  font-size: 0.82rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
}
.btn-ghost::after {
  content: '→';
  transition: 0.2s;
}
.btn-ghost:hover {
  color: var(--ink);
}
.btn-ghost:hover::after {
  transform: translateX(4px);
}

/* stats */
.hero-stats {
  display: flex;
  gap: 2.5rem;
  margin-top: 4rem;
  padding-top: 2rem;
  border-top: 1px solid #ffffff12;
  flex-wrap: wrap;
}
.stat {
  min-width: 80px;
}
.stat-num {
  font-family: 'Oswald';
  font-size: 1.9rem;
  color: var(--ink);
}
.stat-num span {
  color: var(--accent);
}
.stat-label {
  font-size: 0.7rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--muted);
}

/* RIGHT */
.hero-right {
  display: flex;
  justify-content: center;
}

.product-stage {
  position: relative;
  width: 100%;
  max-width: 480px;
  aspect-ratio: 1;
}

/* glow */
.product-stage::after {
  content: '';
  position: absolute;
  width: 60%;
  height: 60%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: radial-gradient(circle, var(--accent) 0%, transparent 70%);
  opacity: 0.12;
  filter: blur(40px);
  z-index: 1;
}

/* rings */
.ring {
  position: absolute;
  border: 1px solid var(--accent);
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: spin linear infinite;
}
.ring-1 {
  width: 88%;
  height: 88%;
  opacity: 0.08;
  animation-duration: 60s;
}
.ring-2 {
  width: 72%;
  height: 72%;
  opacity: 0.12;
  animation-duration: 45s;
  animation-direction: reverse;
}
.ring-3 {
  width: 56%;
  height: 56%;
  opacity: 0.18;
  animation-duration: 30s;
  border-style: dashed;
}

@keyframes spin {
  to {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}

/* product */
.product-img {
  position: absolute;
  width: 80%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.6));
  transition: 0.4s;
}
.product-stage:hover .product-img {
  transform: translate(-50%, -52%) scale(1.04);
}

/* floating cards */
.float-card {
  position: absolute;
  background: #1e3238cc;
  border: 1px solid #ffffff15;
  backdrop-filter: blur(8px);
  padding: 0.7rem 1rem;
  font-family: 'DM Sans';
  animation: float ease-in-out infinite;
}

.fc-1 {
  top: 8%;
  left: 0;
  animation-duration: 4s;
}
.fc-2 {
  bottom: 15%;
  right: -6%;
  animation-duration: 5.5s;
}
.fc-3 {
  top: 50%;
  left: -10%;
  animation-duration: 6s;
}

.fc-label {
  font-size: 0.62rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
}
.fc-val {
  font-size: 0.85rem;
  color: var(--ink);
}
.fc-accent {
  color: var(--accent);
}

@keyframes float {
  0% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px);
  }
  60% {
    transform: translateY(-6px);
  }
  100% {
    transform: translateY(0);
  }
}

/* hover interactions */
.product-stage:hover .ring {
  opacity: 0.25;
}
.product-stage:hover .float-card {
  transform: translateY(-6px) scale(1.03);
}

/* animation */
@keyframes fadeUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* responsive */
@media (max-width: 900px) {
  .hero-inner {
    grid-template-columns: 1fr;
    padding: 4rem 1.5rem;
    gap: 3rem;
  }

  .hero-right {
    order: -1;
  }

  .hero-stats {
    justify-content: space-between;
  }
}
</style>
