import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './assets/main.css'; // Import Tailwind CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import bootstrap from 'bootstrap/dist/js/bootstrap.min.js'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUserSecret, faHome, faCalendarCheck, faInfoCircle, faPhone, faShoppingCart, faReceipt, faPlus } from '@fortawesome/free-solid-svg-icons'
import axios from 'axios'
import 'alertifyjs/build/css/alertify.min.css' // Import AlertifyJS CSS
import 'alertifyjs/build/css/themes/default.min.css' // Import AlertifyJS Default Theme CSS

import App from './App.vue'
import router from './router'

library.add(faUserSecret, faHome, faCalendarCheck, faInfoCircle, faPhone, faShoppingCart, faInfoCircle, faReceipt, faPlus)

// Set default base URL untuk Axios
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

// Enable withCredentials dan with XSRF Token
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const app = createApp(App)

app.use(bootstrap)
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(createPinia())
app.use(router)

// Tambahkan Axios ke Vue prototype
app.config.globalProperties.$axios = axios

app.mount('#app')
