<script setup lang="ts">
import type { Profile } from '@/components/types'
import { onMounted, ref } from 'vue'
import axiosClient from '@/axios'
import { useToaster } from '@/composables/useToast'
import ProgressSpinner from 'primevue/progressspinner'
import router from '@/router'

const { showError, showSuccess } = useToaster()

const emptyProfile: Profile = {
  id: 0,
  name: '',
  email: '',
  customer: {
    id: 0,
    phone: '',
    date_of_birth: '',
    gender: null,
  },
  address: {
    id: 0,
    first_name: '',
    last_name: '',
    company: null,
    address_line_1: '',
    address_line_2: null,
    city: '',
    state: '',
  },
}
const error = ref('')
const profile = ref<Profile>({ ...emptyProfile })
const loading = ref(true)

const normalizeProfile = (data: any): Profile => ({
  ...emptyProfile,
  ...data,
  customer: data.customer ?? emptyProfile.customer,
  address: data.address ?? emptyProfile.address,
})

const saveProfile = async () => {
  loading.value = true
  try {
    const payload = {
      phone: profile.value.customer.phone,
      date_of_birth: profile.value.customer.date_of_birth,
      gender: profile.value.customer.gender,
      first_name: profile.value.address.first_name,
      last_name: profile.value.address.last_name,
      company: profile.value.address.company,
      address_line_1: profile.value.address.address_line_1,
      address_line_2: profile.value.address.address_line_2,
      city: profile.value.address.city,
      state: profile.value.address.state,
    }

    await axiosClient.put('/api/profile', payload)
    showSuccess('Profile updated successfully')
    router.push('/profile')
  } catch (e: any) {
    showError(e.message)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    const response = await axiosClient.get('/api/profile')
    profile.value = normalizeProfile(response.data.data)
  } catch (e: any) {
    showError(e.message)
  } finally {
    loading.value = false
  }
})
</script>
<template>
  <div v-if="loading" class="spinner-wrap">
    <ProgressSpinner style="width: 36px; height: 36px" />
  </div>

  <form v-else class="edit-profile" @submit.prevent="saveProfile">
    <div class="page-header">
      <p class="eyebrow">Account</p>
      <h1 class="page-title">Edit profile</h1>
    </div>

    <!-- Personal Information -->
    <div class="section">
      <p class="section-label">
        <i class="ti ti-user" aria-hidden="true"></i> Personal information
      </p>
      <div class="grid">
        <div class="field">
          <label>First name</label>
          <input v-model="profile.address.first_name" type="text" placeholder="First name" />
        </div>
        <div class="field">
          <label>Last name</label>
          <input v-model="profile.address.last_name" type="text" placeholder="Last name" />
        </div>
        <div class="field">
          <label>Date of birth</label>
          <input v-model="profile.customer.date_of_birth" type="date" />
        </div>
        <div class="field">
          <label>Phone</label>
          <input v-model="profile.customer.phone" type="text" placeholder="+977 98XXXXXXXX" />
        </div>
        <div class="field">
          <label>Gender</label>
          <select v-model="profile.customer.gender">
            <option disabled value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Address -->
    <div class="section">
      <p class="section-label"><i class="ti ti-map-pin" aria-hidden="true"></i> Address</p>
      <div class="grid">
        <div class="field">
          <label>Address line 1</label>
          <input
            v-model="profile.address.address_line_1"
            type="text"
            placeholder="Street address"
          />
        </div>
        <div class="field">
          <label>Address line 2</label>
          <input
            v-model="profile.address.address_line_2"
            type="text"
            placeholder="Apartment, suite, etc."
          />
        </div>
        <div class="field">
          <label>City</label>
          <input v-model="profile.address.city" type="text" placeholder="Kathmandu" />
        </div>
        <div class="field">
          <label>State</label>
          <input v-model="profile.address.state" type="text" placeholder="Bagmati" />
        </div>
        <div class="field">
          <label>Company <span class="optional">(optional)</span></label>
          <input v-model="profile.address.company" type="text" placeholder="Your company" />
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="actions">
      <RouterLink to="/profile" class="btn-cancel">Cancel</RouterLink>
      <button type="submit" class="btn-save" :disabled="loading">
        <i class="ti ti-check" aria-hidden="true"></i>
        Save changes
      </button>
    </div>
  </form>
</template>

<style scoped>
.spinner-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
}

.edit-profile {
  max-width: 860px;
  margin: 2.5rem auto;
  padding: 0 2rem 3rem;
  font-family: 'DM Sans', sans-serif;
}

.page-header {
  margin-bottom: 2rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid var(--border);
}

.eyebrow {
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 0.4rem;
}

.page-title {
  font-family: 'Oswald', sans-serif;
  font-size: 1.5rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: var(--ink);
}

.section {
  margin-bottom: 2rem;
}

.section-label {
  font-size: 0.72rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--muted);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1rem;
}

.section-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.field label {
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--muted);
  letter-spacing: 0.02em;
}

.optional {
  font-weight: 400;
  color: var(--muted);
  opacity: 0.6;
}

.field input,
.field select {
  padding: 0.55rem 0.75rem;
  border-radius: 6px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--ink);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem;
  outline: none;
  transition: border-color 0.15s;
}

.field input:focus,
.field select:focus {
  border-color: var(--accent);
}

.field input::placeholder {
  color: var(--muted);
  opacity: 0.6;
  font-size: 0.85rem;
}

.actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 8px;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border);
  margin-top: 0.5rem;
}

.btn-cancel {
  padding: 0.5rem 1.1rem;
  border-radius: 6px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--muted);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.85rem;
  cursor: pointer;
  text-decoration: none;
  transition:
    border-color 0.15s,
    color 0.15s;
}

.btn-cancel:hover {
  border-color: var(--accent);
  color: var(--ink);
}

.btn-save {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0.5rem 1.2rem;
  border-radius: 6px;
  border: none;
  background: var(--accent);
  color: var(--bg);
  font-family: 'Oswald', sans-serif;
  font-size: 0.85rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  cursor: pointer;
  transition: opacity 0.15s;
}

.btn-save:hover:not(:disabled) {
  opacity: 0.85;
}

.btn-save:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

@media (max-width: 640px) {
  .edit-profile {
    padding: 0 1rem 2rem;
  }

  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
