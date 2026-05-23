import PrimeVue from 'primevue/config'
import ConfirmationService from 'primevue/confirmationservice'
import ToastService from 'primevue/toastservice'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/main.css'
import { definePreset } from '@primeuix/themes'
import Aura from '@primeuix/themes/aura'
import VueGtag from 'vue-gtag'

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
app.use(
  VueGtag,
  {
    config: { id: import.meta.env.VITE_GA_MEASUREMENT_ID },
    enabled: import.meta.env.PROD,
  },
  router,
)
app.use(PrimeVue, {
  theme: {
    preset: MyPreset,
    options: {
      darkModeSelector: '[data-theme="dark"]',
    },
  },
})

app.mount('#app')
