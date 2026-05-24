<script setup lang="ts">
import { Eye, EyeOff } from '@lucide/vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axiosClient from '@/axios'

const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const submit = async () => {
  errors.value = {}
  loading.value = true

  try {
    const response = await axiosClient.post('/api/register', {
      ...form.value,
      email: form.value.email.trim().toLowerCase(),
    })

    const token = response.data.token
    localStorage.setItem('token', token)

    router.push('/login')
  } catch (e: any) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {}
    } else {
      errors.value = {
        general: ['Something went wrong. Please try again.'],
      }
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-wrap">
    <div class="login-card">
      <div class="brand">
        <div class="brand-icon">
          <svg viewBox="0 0 16 16" fill="currentColor">
            <path
              d="M3 2h10a1 1 0 011 1v2H2V3a1 1 0 011-1zM2 6h12v7a1 1 0 01-1 1H3a1 1 0 01-1-1V6zm3 2v1h2V8H5zm0 2v1h2v-1H5z"
            />
          </svg>
        </div>
        <span class="brand-name">GAME GEARS</span>
      </div>

      <h1>Welcome back</h1>
      <p class="subtitle">Create a new account</p>

      <form @submit.prevent="submit" novalidate autocomplete="off">
        <div class="field">
          <label for="name">name</label>
          <input
            id="name"
            v-model="form.name"
            placeholder="John Doe"
            required
            autocomplete="name"
          />
          <p v-if="errors.name" class="error">
            {{ errors.name[0] }}
          </p>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="you@example.com"
            required
            autocomplete="email"
          />
          <p v-if="errors.email" class="error">
            {{ errors.email[0] }}
          </p>
        </div>

        <div class="field">
          <label for="password">Password</label>

          <div class="password-wrapper">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
              autocomplete="new-password"
            />

            <button
              type="button"
              class="toggle-btn"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? 'Hide password' : 'Show password'"
            >
              <Eye v-if="!showPassword" size="18" />
              <EyeOff v-else size="18" />
            </button>
          </div>

          <p v-if="errors.password" class="error">
            {{ errors.password[0] }}
          </p>
        </div>

        <div class="field">
          <label for="password_confirmation">Confirm Password</label>

          <div class="password-wrapper">
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
              autocomplete="new-password"
            />

            <button
              type="button"
              class="toggle-btn"
              @click="showConfirmPassword = !showConfirmPassword"
              :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'"
            >
              <Eye v-if="!showConfirmPassword" size="18" />
              <EyeOff v-else size="18" />
            </button>
          </div>

          <p v-if="errors.password_confirmation" class="error">
            {{ errors.password_confirmation[0] }}
          </p>
        </div>

        <p v-if="errors.general" class="error">
          {{ errors.general[0] }}
        </p>

        <button type="submit" :disabled="loading">
          {{ loading ? 'Creating account…' : 'Create account' }}
        </button>
      </form>

      <div class="divider">
        <hr />
        <span>or</span>
        <hr />
      </div>

      <p class="register-link">
        Already have an account?
        <router-link to="/login">Sign in</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-wrapper input {
  width: 100%;
  padding-right: 60px;
}

.toggle-btn {
  position: absolute;
  right: 10px;
  background: transparent;
  border: none;
  color: #c8a96e;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
}

.login-wrap {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #253439;
  padding: 1.5rem;
  width: 100%;
}

.login-card {
  background: #1a272b;
  border: 1px solid rgba(255, 255, 255, 0.07);
  border-radius: 14px;
  padding: 2.25rem 2.5rem;
  width: 100%;
  max-width: 400px;
}

/* Brand */
.brand {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1.75rem;
}
.brand-icon {
  width: 28px;
  height: 28px;
  background: #c8a96e;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #253439;
}
.brand-icon svg {
  width: 15px;
  height: 15px;
}
.brand-name {
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1em;
  color: #c8a96e;
}

/* Headings */
h1 {
  font-size: 22px;
  font-weight: 600;
  color: #f0ece4;
  margin: 0 0 0.25rem;
}
.subtitle {
  font-size: 14px;
  color: rgba(240, 236, 228, 0.45);
  margin: 0 0 1.75rem;
}

/* Fields */
.field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 1rem;
}
.field label {
  font-size: 13px;
  color: rgba(240, 236, 228, 0.6);
}
.field input {
  height: 40px;
  padding: 0 12px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  color: #f0ece4;
  font-size: 14px;
  outline: none;
  transition: border-color 0.15s;
}
.field input::placeholder {
  color: rgba(240, 236, 228, 0.25);
}
.field input:focus {
  border-color: #c8a96e;
  box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.12);
}

/* Forgot */
.forgot-row {
  text-align: right;
  margin-top: -0.5rem;
  margin-bottom: 1.25rem;
}
.forgot-row a {
  font-size: 12px;
  color: #c8a96e;
  text-decoration: none;
}
.forgot-row a:hover {
  text-decoration: underline;
}

/* Error */
.error {
  font-size: 13px;
  color: #f09595;
  margin: 0 0 0.75rem;
}

/* Submit */
button[type='submit'] {
  width: 100%;
  height: 42px;
  background: #c8a96e;
  color: #253439;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.02em;
  cursor: pointer;
  transition: opacity 0.15s;
}
button[type='submit']:hover:not(:disabled) {
  opacity: 0.88;
}
button[type='submit']:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Divider */
.divider {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 1.5rem 0;
}
.divider hr {
  flex: 1;
  border: none;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
}
.divider span {
  font-size: 12px;
  color: rgba(240, 236, 228, 0.3);
}

/* Register */
.register-link {
  font-size: 13px;
  color: rgba(240, 236, 228, 0.45);
  text-align: center;
  margin: 0;
}
.register-link a {
  color: #c8a96e;
  text-decoration: none;
}
.register-link a:hover {
  text-decoration: underline;
}
</style>
