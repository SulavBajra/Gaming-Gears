import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice'
import Aura from '@primeuix/themes/aura'
import { definePreset } from '@primeuix/themes'

const MyPreset = definePreset(Aura, {
  components: {
    select: {
      root: {
        background: '{surface.0}',
        color: '{surface.900}',
        borderColor: '{surface.300}',
      },
      overlay: {
        background: '{surface.0}',
        borderColor: '{surface.300}',
        color: '{surface.900}',
      },
      option: {
        color: '{surface.800}',
        focusBackground: '{primary.color}',
        focusColor: '#ffffff',
        selectedBackground: '{primary.color}',
        selectedColor: '#ffffff',
      },
      placeholder: {
        color: '{surface.400}',
      },
    },
    inputtext: {
      root: {
        background: '{surface.0}',
        color: '{surface.900}',
        borderColor: '{surface.300}',
        placeholderColor: '{surface.400}',
      },
    },
  },
} as any)

const app = createApp(App)
app.use(router)
app.use(ToastService)
app.use(ConfirmationService)
app.use(PrimeVue, {
  theme: {
    preset: MyPreset,
    options: {
      darkModeSelector: '[data-theme="dark"]',
    },
  },
})
app.mount('#app')
