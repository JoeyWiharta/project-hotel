<template>
    <div>
      <h2>User Dashboard</h2>
      <p>Welcome to the user dashboard!</p>
      <button @click="logout">Logout</button>
    </div>
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router'
  import { useUserStore } from '../../stores/counter'
  import axios from 'axios'
  
  const router = useRouter()
  const store = useUserStore()
  
  function logout() {
    axios({
      url: 'http://localhost:8000/api/logout', // Sesuaikan URL dengan server Laravel Anda
      method: 'post',
      headers: {
        'Authorization': `Bearer ${store.token}`,
        'Content-Type': 'application/json'
      }
    }).then(response => {
      console.log(response.data) // hanya untuk pengembangan
      store.logout() // Panggil fungsi logout dari store
      router.push('/') // Arahkan ke halaman login setelah logout
    })
    .catch(error => {
      console.log('AJAX ' + error)
    })
  }
  </script>
  