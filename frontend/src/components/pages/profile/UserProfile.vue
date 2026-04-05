<script setup lang="ts">
import axiosClient from '@/axios'
import { onMounted, ref } from 'vue'
import type { Profile } from '@/components/types'
import { useToaster } from '@/composables/useToast'
import ProfileForm from '@/components/pages/profile/ProfileForm.vue'
import ProfileData from '@/components/pages/profile/ProfileData.vue'
import ProgressSpinner from 'primevue/progressspinner'
import { useRouter } from 'vue-router'

const { showError, showSuccess } = useToaster()
const profile = ref<Profile | null>(null)
const loading = ref(true)
const router = useRouter()

onMounted(async () => {
  try {
    const response = await axiosClient.get('/api/profile')
    profile.value = response.data.data
  } catch (error) {
    router.push('/profile/create')
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div v-if="loading">
    <ProgressSpinner class="spinner-load" />
  </div>
  <div v-else-if="profile">
    <ProfileData :profile="profile" />
  </div>
  <ProfileForm v-else />
</template>

<style module>
.tabs {
  border-radius: 20px;
}
</style>
