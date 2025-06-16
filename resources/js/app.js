import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap'
import App from './App.vue'
import router from './routes/router.js'
const app = createApp(App)
app.use(router)
app.mount('#app')
