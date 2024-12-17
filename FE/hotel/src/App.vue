<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useUserStore } from './stores/counter'
import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';

const theroute = useRoute();
const store = useUserStore();

// Ambil role dari state Vuex (atau sesuaikan dengan implementasi login Anda)
const userRole = computed(() => store.role || 'guest');

// Tentukan apakah user sudah login dan bukan di halaman login
const isNotLoginPage = computed(() => theroute.name !== 'login');

// Tentukan apakah peran adalah "admin"
const isAdmin = computed(() => userRole.value === 'admin');

// Tentukan apakah peran adalah "user"
const isUser = computed(() => userRole.value === 'user' && theroute.name !== 'userdashboard');
</script>

<template>
    <div class="relative ">
      <div v-if="isNotLoginPage" >   
        <!-- Tampilkan Sidebar jika admin -->
        <Sidebar v-if="isAdmin"/>
        <!-- Tampilkan Navbar jika user -->
        <Navbar v-if="isUser" />
      </div>
  
      <!-- Tempat untuk menampilkan konten sesuai routing -->
      <div class="w-full">
        <router-view></router-view>
      </div>
    </div>
  </template>
