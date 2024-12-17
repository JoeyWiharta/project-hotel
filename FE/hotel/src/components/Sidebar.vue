<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/counter';
import axios from 'axios';

const router = useRouter();
const store = useUserStore();
const activeButton = ref(''); // Menyimpan tombol aktif

function navigateToRooms() {
  router.push('/rooms'); // Arahkan ke halaman CRUD Room
  activeButton.value = 'rooms'; // Set tombol aktif
}

function navigateToAdminDatabase() {
  router.push('/admin-database'); // Arahkan ke halaman CRUD Room
  activeButton.value = 'adminDatabase'; // Set tombol aktif
}

function dashboard() {
  router.push('/admin-dashboard');
  activeButton.value = 'dashboard'; // Set tombol aktif
}

function viewRooms() {
  router.push('/view-rooms'); // Arahkan ke halaman View Rooms dalam bentuk kartu
  activeButton.value = 'view-rooms'; // Set tombol aktif
}

function adminHistory() {
  router.push('/admin-history'); // Arahkan ke halaman View Rooms dalam bentuk kartu
  activeButton.value = 'admin-history'; // Set tombol aktif
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
  <div class="fixed left-0 min-h-screen w-1/6 border border-r-4 shadow-lg border-slate-300 bg-blend-lighten">
    <!-- Logo -->
    <div class="flex justify-center md:justify-start mt-10 mb-20 px-2 lg:mx-6 items-center">
      <img src="/images/iconHotel.png" alt="" class="lg:max-w-9 max-w-8 mb-2" />
      <h5 class="lg:ml-4 text-[#1366D9] hidden md:block text-sm lg:text-2xl font-poppins">Alicia Hotel</h5>
    </div>

    <!-- Buttons -->
    <div
      class="flex justify-center md:justify-start px-2 lg:mx-6 mt-4 text-center rounded-lg p-2"
      :class="{ 'bg-blue-200': activeButton === 'dashboard' }"
      @click="dashboard"
    >
      <img src="../assets/dashboard.svg" alt="" />
      <button class="lg:ml-4 hidden md:block text-xs ml-2 lg:text-lg text-[#1366D9] font-poppins">Dashboard</button>
    </div>

    <div
      class="flex justify-center md:justify-start px-2 lg:mx-6 mt-4 text-center rounded-lg p-2"
      :class="{ 'bg-blue-200': activeButton === 'adminDatabase' }"
      @click="navigateToAdminDatabase"
    >
      <img src="../assets/database.svg" alt="" />
      <button class="lg:ml-4 hidden md:block text-xs ml-2 lg:text-lg text-[#1366D9] font-poppins">Database</button>
    </div>

    <div
      class="flex px-2 justify-center md:justify-start lg:mx-6 mt-4 rounded-lg p-2"
      :class="{ 'bg-blue-200': activeButton === 'rooms' }"
      @click="navigateToRooms"
    >
      <img src="../assets/manageRoom.svg" alt="" />
      <button class="lg:ml-4 hidden md:block text-xs ml-2 lg:text-lg text-[#1366D9] font-poppins">Manage Rooms</button>
    </div>

    <div
      class="flex justify-center md:justify-start px-2 lg:mx-6 mt-4 rounded-lg p-2"
      :class="{ 'bg-blue-200': activeButton === 'view-rooms' }"
      @click="viewRooms"
    >
      <img src="../assets/room.svg" alt="" />
      <button class="lg:ml-4 hidden md:block text-xs ml-7 lg:text-lg text-[#1366D9] font-poppins">Rooms</button>
    </div>

    <div
      class="flex justify-center md:justify-start px-2 lg:mx-6 mt-4 rounded-lg p-2"
      :class="{ 'bg-blue-200': activeButton === 'admin-history' }"
      @click="adminHistory"
    >
      <img src="../assets/history.png" alt="" />
      <button class="lg:ml-4 hidden md:block text-xs ml-7 lg:text-lg text-[#1366D9] font-poppins">History</button>
    </div>

    <!-- Logout -->
    <div
      class="rounded-xl bg-[#1366D9] flex justify-center items-center lg:mx-14 mx-4 mt-40 p-2 cursor-pointer"
      @click="logout"
    >
      <button class="text-white lg:ml-4 hidden md:block text-xs ml-7 lg:text-lg font-poppins">Logout</button>
      <img src="../assets/logout.svg" alt="" class="lg:ml-4 md:ml-2" />
    </div>
  </div>
</template>
