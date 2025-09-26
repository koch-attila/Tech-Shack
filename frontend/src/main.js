import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { router } from '@/router/index.js'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { plugin, defaultConfig } from '@formkit/vue'
import config from '../formkit.config.js'
import { http } from '@utils/http.mjs'

import App from '@/App.vue'

import '@assets/app.scss'

const app = createApp(App)
app.config.globalProperties.$backendUrl = "http://backend.vm1.test";

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

app.use(router)
app.use(plugin, defaultConfig(config))
app.use(pinia)

await http.get('http://backend.vm1.test/sanctum/csrf-cookie', { withCredentials: true });

app.mount('#app')