<template>
    <div>
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div>
          <label>Name:</label>
          <input type="text" v-model="name" required>
        </div>
        <div>
          <label>Email:</label>
          <input type="email" v-model="email" required>
        </div>
        <div>
          <label>Password:</label>
          <input type="password" v-model="password" required>
        </div>
        <button type="submit">Register</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '../stores/counter'
  
  // Atur konfigurasi Axios
  axios.defaults.withCredentials = true;
  axios.defaults.withXSRFToken = true;
  
  const router = useRouter()
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const store = useUserStore()
  
  const customConfig = {
    'Content-Type': 'application/json',
  };
  
  function register() {
    axios({
      url: 'http://localhost:8000/api/register', // Sesuaikan URL dengan server Laravel Anda
      method: 'post',
      data: {
        name: name.value,
        email: email.value,
        password: password.value
      },
      headers: customConfig
    }).then(response => {
      console.log(response.data) // only for development
  
      if (response.data.success === true) {
        store.email = email.value
        store.token = response.data.data
        router.push('/')
      }
    })
    .catch(error => {
      console.log('AJAX ' + error)
    })
    .finally()
  }
  </script>
  