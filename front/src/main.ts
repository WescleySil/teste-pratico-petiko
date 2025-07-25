import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import { createErrorHandler } from './utils/errorHandler'

const app = createApp(App)

createErrorHandler(router)

app.use(router)
app.mount('#app')
