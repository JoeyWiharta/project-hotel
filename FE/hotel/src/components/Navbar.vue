<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/counter';
import axios from 'axios';

const router = useRouter();
const store = useUserStore();

const itemNavbar = ref([
  {
    id: 1,
    name: 'Home',
    icon: 'home',
    path: '/user-dashboard',
  },
  {
    id: 2,
    name: 'Booking',
    icon: 'calendar-check',
    path: '/user-booking',
  },
  {
    id: 3,
    name: 'About',
    icon: 'info-circle',
    path: '/about',
  },
  {
    id: 4,
    name: 'Contact',
    icon: 'phone',
    path: '/contact',
  },
  {
    id: 5,
    name: 'Order',
    icon: 'receipt',
    path: '/user-order',
  },
]);

function navigateTo(path) {
  if (path) router.push(path);
}

function logout() {
  axios({
    url: 'http://localhost:8000/api/logout', // Sesuaikan URL dengan server Laravel Anda
    method: 'post',
    headers: {
      Authorization: `Bearer ${store.token}`,
      'Content-Type': 'application/json',
    },
  })
    .then((response) => {
      console.log(response.data); // hanya untuk pengembangan
      store.logout(); // Panggil fungsi logout dari store
      router.push('/'); // Arahkan ke halaman login setelah logout
    })
    .catch((error) => {
      console.log('AJAX ' + error);
    });
}
</script>

<template>
  <nav
    class="w-full h-16 fixed bg-[#006A96] z-10 top-0 flex justify-between items-center md:py-10 text-center"
  >
    <!-- Logo -->
    <div class="flex items-center justify-center text-center ml-10">
      <img
        src="/images/iconHotelWhite.png"
        alt=""
        class="md:max-w-10 max-w-7 md:ml-3"
      />
      <h1 class="md:text-[1.5rem] text-sm ml-3 text-white">Alicia Hotel</h1>
    </div>

    <!-- Navbar Items -->
    <div class="flex">
      <div
        class="flex mr-3 md:mr-10 text-white items-center hover:scale-110 duration-100 ease-in-out cursor-pointer"
        v-for="item in itemNavbar"
        :key="item.id"
        @click="navigateTo(item.path)"
      >
        <font-awesome-icon :icon="item.icon" class="text-white mr-2" />
        <span class="no-underline text-white hidden md:block ">
          {{ item.name }}
        </span>
      </div>

      <!-- Logout Button -->
      <button
        class="mr-3 bg-red-500 text-white p-2 rounded-lg cursor-pointer md:text-xl"
        @click="logout"
      >
        Logout
      </button>
    </div>
  </nav>
</template>
