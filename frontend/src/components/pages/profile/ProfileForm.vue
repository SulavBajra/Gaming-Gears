<script setup lang="ts">
import { ref } from 'vue'
import Card from 'primevue/card'
import axiosClient from '@/axios'
import { useToaster } from '@/composables/useToast'
import { useRouter } from 'vue-router'

const { showSuccess, showError } = useToaster()
const formData = ref({
  first_name: '',
  last_name: '',
  phone: '',
  date_of_birth: '',
  gender: '',
  address_line_1: '',
  address_line_2: '',
  city: '',
  state: '',
  company: '',
})
const loading = ref(false)
const router = useRouter()
const validationErrors = ref<Record<string, string[]>>({})

const submitProfile = async () => {
  // Clear previous errors
  validationErrors.value = {}
  loading.value = true

  try {
    await axiosClient.post('/api/profile', formData.value)
    showSuccess('Profile Created Successfully')
    router.push('/profile')
  } catch (e: any) {
    if (e.response?.status === 422) {
      validationErrors.value = e.response.data.errors

      const firstError = Object.values(validationErrors.value)[0]?.[0]
      if (firstError) {
        showError(firstError)
      } else {
        showError('Please check your form for errors')
      }
      console.log('Validation Errors:', validationErrors.value)
    } else {
      showError(e.response?.data?.message || e.message || 'An error occurred')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Card :class="$style.card">
    <template #title>
      <h2 :class="$style.title">Create Profile</h2>
    </template>
    <template #content>
      <form @submit.prevent="submitProfile" :class="$style.form">
        <div v-if="Object.keys(validationErrors).length" :class="$style.errorSummary">
          <strong>Please fix the following errors:</strong>
          <ul>
            <li v-for="(errors, field) in validationErrors" :key="field">
              {{ field.replace(/_/g, ' ') }}: {{ errors[0] }}
            </li>
          </ul>
        </div>

        <div :class="$style.section">
          <h3 :class="$style.sectionTitle">Personal Information</h3>
          <div :class="$style.formGrid">
            <div :class="[$style.formField, { [$style.hasError]: validationErrors.first_name }]">
              <label>First Name</label>
              <input v-model="formData.first_name" type="text" required />
              <span v-if="validationErrors.first_name" :class="$style.errorMessage">
                {{ validationErrors.first_name[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.last_name }]">
              <label>Last Name</label>
              <input v-model="formData.last_name" type="text" required />
              <span v-if="validationErrors.last_name" :class="$style.errorMessage">
                {{ validationErrors.last_name[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.phone }]">
              <label>Phone (10 digits)</label>
              <input v-model="formData.phone" type="tel" placeholder="9810000000" required />
              <span v-if="validationErrors.phone" :class="$style.errorMessage">
                {{ validationErrors.phone[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.date_of_birth }]">
              <label>Date of Birth</label>
              <input v-model="formData.date_of_birth" type="date" required />
              <span v-if="validationErrors.date_of_birth" :class="$style.errorMessage">
                {{ validationErrors.date_of_birth[0] }}
              </span>
            </div>
            <div :class="[$style.formField, { [$style.hasError]: validationErrors.gender }]">
              <label>Gender</label>
              <select v-model="formData.gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
              <span v-if="validationErrors.gender" :class="$style.errorMessage">
                {{ validationErrors.gender[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.company }]">
              <label>Company (Optional)</label>
              <input v-model="formData.company" type="text" />
              <span v-if="validationErrors.company" :class="$style.errorMessage">
                {{ validationErrors.company[0] }}
              </span>
            </div>
          </div>
        </div>

        <div :class="$style.section">
          <h3 :class="$style.sectionTitle">Address Information</h3>
          <div :class="$style.formGrid">
            <div
              :class="[
                $style.formFieldFull,
                { [$style.hasError]: validationErrors.address_line_1 },
              ]"
            >
              <label>Address Line 1</label>
              <input v-model="formData.address_line_1" type="text" required />
              <span v-if="validationErrors.address_line_1" :class="$style.errorMessage">
                {{ validationErrors.address_line_1[0] }}
              </span>
            </div>

            <div
              :class="[
                $style.formFieldFull,
                { [$style.hasError]: validationErrors.address_line_2 },
              ]"
            >
              <label>Address Line 2 (Optional)</label>
              <input v-model="formData.address_line_2" type="text" />
              <span v-if="validationErrors.address_line_2" :class="$style.errorMessage">
                {{ validationErrors.address_line_2[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.city }]">
              <label>City</label>
              <input v-model="formData.city" type="text" required />
              <span v-if="validationErrors.city" :class="$style.errorMessage">
                {{ validationErrors.city[0] }}
              </span>
            </div>

            <div :class="[$style.formField, { [$style.hasError]: validationErrors.state }]">
              <label>State</label>
              <input v-model="formData.state" type="text" required />
              <span v-if="validationErrors.state" :class="$style.errorMessage">
                {{ validationErrors.state[0] }}
              </span>
            </div>
          </div>
        </div>

        <div :class="$style.formActions">
          <button type="button" @click="router.back()" :class="$style.cancelBtn">Cancel</button>
          <button type="submit" :disabled="loading" :class="$style.submitBtn">
            {{ loading ? 'Creating...' : 'Create Profile' }}
          </button>
        </div>
      </form>
    </template>
  </Card>
</template>
<style module>
.card {
  max-width: 900px;
  margin: 1rem auto;
  background: var(--bg);
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.title {
  color: var(--accent);
  font-size: 1.75rem;
  font-weight: 600;
  margin: 0;
  padding-bottom: 1rem;
  border-bottom: 2px solid var(--border);
}

.form {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.section {
  background: rgba(255, 255, 255, 0.03);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid var(--border);
}

.sectionTitle {
  color: var(--accent);
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0 0 1.5rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--border);
}

.formGrid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.25rem;
}

.formField,
.formFieldFull {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.formFieldFull {
  grid-column: 1 / -1;
}

.formField label,
.formFieldFull label {
  font-size: 0.85rem;
  font-weight: 500;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.formField input,
.formFieldFull input,
.formField select,
.formFieldFull select {
  padding: 0.75rem 1rem;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--ink);
  font-size: 0.95rem;
  transition: all 0.2s ease;
}

.formField input:focus,
.formFieldFull input:focus,
.formField select:focus,
.formFieldFull select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.1);
}

.formField input:hover,
.formFieldFull input:hover,
.formField select:hover,
.formFieldFull select:hover {
  border-color: var(--accent);
}

/* Error styles */
.hasError input,
.hasError select {
  border-color: #ef4444;
}

.hasError input:focus,
.hasError select:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.errorMessage {
  color: #ef4444;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.errorSummary {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid #ef4444;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
  color: #ef4444;
}

.errorSummary ul {
  margin: 0.5rem 0 0 1.5rem;
}

.formActions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 1rem;
}

.cancelBtn,
.submitBtn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.cancelBtn {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--muted);
}

.cancelBtn:hover {
  background: rgba(255, 255, 255, 0.05);
  border-color: var(--accent);
  color: var(--accent);
}

.submitBtn {
  background: var(--accent);
  color: white;
}

.submitBtn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(200, 169, 110, 0.3);
}

.submitBtn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 768px) {
  .card {
    margin: 1rem;
  }

  .formGrid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .section {
    padding: 1rem;
  }

  .formActions {
    flex-direction: column;
  }

  .cancelBtn,
  .submitBtn {
    width: 100%;
  }

  .title {
    font-size: 1.5rem;
  }
}

/* Loading state styling */
.submitBtn:disabled {
  background: var(--muted);
  transform: none;
}
</style>
