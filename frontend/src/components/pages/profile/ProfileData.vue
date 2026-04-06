<script setup lang="ts">
import type { Profile } from '@/components/types'
import { useRouter } from 'vue-router'

const router = useRouter()
const props = defineProps<{
  profile: Profile
}>()

const editProfile = () => {
  router.push('/profile/edit')
}
</script>

<template>
  <section class="profile-data">
    <h2>Your Profile</h2>
    <div class="profile-header">
      <p><strong>Email:</strong> {{ profile.email }}</p>
      <p><strong>User Name:</strong> {{ profile.name }}</p>
      <p><strong>First Name:</strong> {{ profile.address?.first_name }}</p>
      <p><strong>Last Name:</strong> {{ profile.address?.last_name }}</p>
      <p><strong>D.O.B:</strong> {{ profile.customer?.date_of_birth }}</p>
      <p><strong>Gender:</strong> {{ profile.customer?.gender }}</p>
      <p><strong>Phone:</strong> {{ profile.customer?.phone }}</p>
    </div>
    <div class="address-info">
      <h3>Address Information</h3>
      <div class="address-details">
        <p><strong>Address Line 1:</strong> {{ profile.address?.address_line_1 }}</p>
        <p><strong>Address Line 2:</strong> {{ profile.address?.address_line_2 || '—' }}</p>
        <p><strong>City:</strong> {{ profile.address?.city }}</p>
        <p><strong>State:</strong> {{ profile.address?.state }}</p>
      </div>
      <div class="edit-profile">
        <button @click="editProfile">Edit Profile</button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.profile-data {
  padding: 2rem 2rem 0.5rem 2rem;
  background-color: var(--bg);
  border-radius: 12px;
  color: var(--ink);
  border: 1px solid var(--border);
}

.profile-data h2 {
  margin-bottom: 1.5rem;
  font-size: 1.75rem;
  color: var(--accent);
  border-bottom: 2px solid var(--accent);
  padding-bottom: 0.5rem;
  display: inline-block;
}

.profile-data h3 {
  margin: 1.5rem 0 1rem;
  font-size: 1.25rem;
  color: var(--accent);
}

.profile-header {
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

.profile-header p,
.info-grid p,
.address-details p {
  margin: 0.5rem 0;
  line-height: 1.5;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 0.75rem;
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 8px;
}

.address-details {
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 8px;
}

strong {
  color: var(--accent);
  font-weight: 600;
  margin-right: 0.5rem;
}

.edit-profile {
  text-align: right;
  padding: 10px;
}

.edit-profile button {
  border-radius: 10px;
  padding: 0.5rem 1rem;
  background-color: var(--accent);
  color: var(--bg);
  border: none;
  cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
  .profile-data {
    margin: 1rem;
    padding: 1.5rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .profile-data h2 {
    font-size: 1.5rem;
  }
}
</style>
