<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label>Email:</label>
        <input type="email" v-model="username" required>
      </div>
      <div>
        <label>Password:</label>
        <input type="password" v-model="password" required>
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'

// Atur konfigurasi Axios
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const router = useRouter()
const auth = ref(null)
const username = ref('')
const password = ref('')
const store = useUserStore()

const customConfig = {
  'Content-Type': 'application/json',
};

const bodyParameters = computed(() => {
  return {'email': username.value, 'password': password.value}
});

function login() {
  axios({
    url: 'http://localhost:8000/api/login', // Sesuaikan URL dengan server Laravel Anda
    method: 'post',
    data: bodyParameters.value,
    headers: customConfig
  }).then(response => {
    auth.value = response.data
    console.log(response.data) // hanya untuk pengembangan

    if (auth.value.success === true) {
      store.email = username.value
      store.token = auth.value.data.token
      store.role = auth.value.data.role // Menyimpan role pengguna di store

      // Set token sebagai header default untuk semua permintaan Axios
      axios.defaults.headers.common['Authorization'] = `Bearer ${store.token}`

      console.log('Role:', store.role) // Debugging tambahan
      console.log('Navigating to dashboard') // Debugging tambahan

      if (store.role === 'admin') {
        router.push('/admin-dashboard')
      } else {
        router.push('/user-dashboard')
      }
    }
  })
  .catch(error => {
    console.log('AJAX ' + error)
  })
  .finally()
}
</script>
