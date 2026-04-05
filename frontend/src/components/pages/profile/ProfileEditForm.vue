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
  <form @submit.prevent="saveProfile">
    <div v-if="loading">
      <ProgressSpinner />
    </div>

    <div v-else>
      <h3>User Information</h3>
      <div class="user-info">
        <label>First Name</label>
        <input v-model="profile.address.first_name" type="text" placeholder="First Name" />
        <label>Last Name</label>
        <input v-model="profile.address.last_name" type="text" placeholder="Last Name" />
        <label>Date of Birth</label>
        <input v-model="profile.customer.date_of_birth" type="date" />
        <label>Phone</label>
        <input v-model="profile.customer.phone" type="text" placeholder="Phone" />
        <label>Gender</label>
        <select v-model="profile.customer.gender">
          <option disabled value="">Select gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      <h3>Address</h3>

      <div class="user-address">
        <label>Address Line 1</label>
        <input v-model="profile.address.address_line_1" type="text" placeholder="Address Line 1" />
        <label>Address Line 2</label>
        <input v-model="profile.address.address_line_2" type="text" placeholder="Address Line 2" />
        <label>City</label>
        <input v-model="profile.address.city" type="text" placeholder="City" />
        <label>Company</label>
        <input v-model="profile.address.company" type="text" placeholder="Company" />
        <label>State</label>
        <input v-model="profile.address.state" type="text" placeholder="State" />
      </div>

      <button type="submit">Save</button>
    </div>
  </form>
</template>
<style scoped>
/* Container */
form {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  background: var(--bg);
  border-radius: 10px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

/* Section titles */
h3 {
  font-size: 1rem;
  font-weight: 600;
  margin: 1.5rem 0 1rem;
  color: var(--accent);
  border-bottom: 2px solid var(--border, #eee);
  padding-bottom: 0.4rem;
}

.user-info,
.user-address {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.9rem 1.2rem;
}

.full {
  grid-column: 1 / -1;
}

/* Inputs */
input,
select {
  width: 100%;
  padding: 0.55rem 0.65rem;
  border-radius: 6px;
  border: 1px solid var(--border, #ddd);
  font-size: 0.85rem;
  transition: all 0.15s ease;
}

/* Focus state */
input:focus,
select:focus {
  outline: none;
  border-color: var(--accent, #6366f1);
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
}

/* Placeholder */
input::placeholder {
  color: #aaa;
  font-size: 0.8rem;
}

/* Button */
button {
  margin-top: 1.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  border: none;
  background: var(--accent, #6366f1);
  color: white;
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

/* Button hover */
button:hover {
  background: #4f46e5;
}

/* Disabled state */
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Spinner center */
.spinner-load {
  display: flex;
  justify-content: center;
  padding: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
  form {
    margin: 1rem;
    padding: 1.2rem;
  }

  .user-info,
  .user-address {
    grid-template-columns: 1fr;
  }
}
</style>
