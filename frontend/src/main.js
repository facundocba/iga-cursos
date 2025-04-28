import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import api from './services/api'

// Importar Bootstrap JS
import 'bootstrap'

// Configurar axios globalmente
const app = createApp(App)
app.config.globalProperties.$axios = axios
app.config.globalProperties.$api = api

// Montar la aplicación
app.use(store)
   .use(router)
   .mount('#app')