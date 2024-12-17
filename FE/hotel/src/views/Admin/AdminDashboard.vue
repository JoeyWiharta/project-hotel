<template>
  <div class="absolute right-0 w-5/6 pl-10 bg-[#F4F6FF] min-h-screen" >
    <div class="flex justify-between mt-4">
      <div>
        <h2 class="text-2xl md:text-4xl" style="color: #1366D9" >ADMIN DASHBORAD</h2>
        <p class="text-md md:text-lg">Welcome to the admin dashboard!</p>
      </div>
      <div class="mr-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512" class="ml-2 max-w-5 md:max-w-10">
            <path fill="#2c47d1" fill-rule="evenodd" d="M256 42.667A213.333 213.333 0 0 1 469.334 256c0 117.821-95.513 213.334-213.334 213.334c-117.82 0-213.333-95.513-213.333-213.334C42.667 138.18 138.18 42.667 256 42.667m21.334 234.667h-42.667c-52.815 0-98.158 31.987-117.715 77.648c30.944 43.391 81.692 71.685 139.048 71.685s108.104-28.294 139.049-71.688c-19.557-45.658-64.9-77.645-117.715-77.645M256 106.667c-35.346 0-64 28.654-64 64s28.654 64 64 64s64-28.654 64-64s-28.653-64-64-64"/>
          </svg>
            <p class="text-[#1366D9]">Admin</p>
      </div>
    </div>

    <div class="mt-10"> 
      <!-- overview -->
      <div class="  mr-4 lg:mr-10 rounded-xl bg-white shadow-lg p-4">
        <h2 class="font-poppins">Overview</h2>
        <div class="flex flex-wrap mb-2">
            <div 
            class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4 lg:mr-10 shadow-xl mt-4 lg:w-1/4 bg-[#1366D9] text-white hover:scale-105 hover:bg-[#87A2FF] ease-in-out transform duration-200">
              <p class="font-semibold text-lg">Available Room</p>
              <p class="text-md font-bold">Total Room <span class="text-2xl ml-4">{{ availableRooms  }}</span> </p>
            </div>
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4 lg:mr-10 shadow-xl mt-4 lg:w-1/4 bg-[#1366D9] text-white
            hover:scale-105 hover:bg-[#87A2FF] ease-in-out transform duration-200">
              <p class="font-semibold text-lg">Unavailable Room</p>
              <p class="text-md font-bold">Total Room <span class="text-2xl ml-4">{{ unavailableRooms  }}</span> </p>
            </div>
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4 lg:mr-10 shadow-xl mt-4 lg:w-1/4 bg-[#1366D9] text-white
            hover:scale-105 hover:bg-[#87A2FF] ease-in-out transform duration-200">
              <p class="font-semibold text-lg">Booked Room</p>
              <p class="text-md font-bold">Total Room <span class="text-2xl ml-4">{{ bookingRooms  }}</span> </p>
            </div>
        </div>
      </div>

     <!-- room  -->
      <div class="mt-8">
        <div class="  mr-4 lg:mr-10 rounded-xl bg-white shadow-xl p-4">
        <h2 class="font-poppins">Rooms</h2>
        <div class="flex flex-wrap mb-2">
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4  lg:mr-10 shadow-xl mt-4 lg:w-1/5">
              <p class="font-semibold text-xl">Single Room</p>
              <p class="text-lg">Booked {{ singleRoomBookings  }}</p>
              <p class="text-[#1366D9] text-xl font-poppins font-semibold">IDR 150.000 <span class="text-black">/days</span></p>
            </div>
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4  lg:mr-10 shadow-xl mt-4 lg:w-1/5 ">
              <p class="font-semibold text-xl">Standard Room</p>
              <p class="text-lg">Booked {{ standardRoomBookings   }}</p>
              <p class="text-[#1366D9] text-xl font-poppins font-semibold">IDR 300.000 <span class="text-black">/days</span></p>
            </div>
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4  lg:mr-10 shadow-xl mt-4 lg:w-1/5">
              <p class="font-semibold text-xl">Double Room</p>
              <p class="text-lg">Booked {{ doubleRoomBookings   }}</p>
              <p class="text-[#1366D9] text-xl font-poppins font-semibold">IDR 450.000 <span class="text-black">/days</span></p>
            </div>
            <div class="border-2 border-slate-800 rounded-lg p-2 w-full mr-4 lg:mr-10 shadow-xl mt-4 lg:w-1/5">
              <p class="font-semibold text-xl">Deluxe Room</p>
              <p class="text-lg">Booked {{ deluxeRoomBookings   }}</p>
              <p class="text-[#1366D9] text-xl font-poppins font-semibold">IDR 600.000 <span class="text-black">/days</span></p>
            </div>
        </div>
      </div>
      </div>


    </div>
    
  </div>

  
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/counter';
import axios from 'axios';

const router = useRouter();
const store = useUserStore();

const availableRooms = ref(0);
const unavailableRooms = ref(0);
const bookingRooms = ref(0);
const singleRoomBookings = ref(0);
const standardRoomBookings = ref(0);
const doubleRoomBookings = ref(0);
const deluxeRoomBookings = ref(0);

onMounted(async () => {
  console.log('Component mounted'); // Log saat komponen dimuat
  await fetchRoomData();
  await fetchBookingData();
})


async function fetchRoomData() {
  try{
    console.log('Token saat fetchRoomData:', store.token); // Log token untuk verifikasi

    const availableResponse = await axios.get('http://localhost:8000/api/rooms', {
      headers: {
        Authorization: `Bearer ${store.token}`, // Memastikan format header benar
      },
      params: { 
        status: 'Available', // Menyertakan parameter status
      },
    });

    console.log('Available rooms response:', availableResponse.data); // Log data untuk verifikasi

    if (Array.isArray(availableResponse.data.data)) {
      availableRooms.value = availableResponse.data.data.length;
    }

    const unavailableResponse = await axios.get('http://localhost:8000/api/rooms', {
      headers: {
        'Authorization': `Bearer ${store.token}`,
      },
      params: {
        status: 'Unavailable'
        }
      });

      if (Array.isArray(unavailableResponse.data.data)) {
          unavailableRooms.value = unavailableResponse.data.data.length;
      }
      } catch (error) {
        console.error('Error fetching room data:', error);
      }
  }

  async function fetchBookingData() {
  try {
    console.log('Token saat fetchBookingData:', store.token); // Log token untuk verifikasi

    const bookingResponse = await axios.get('http://localhost:8000/api/bookings/admin/all', {
      headers: {
        'Authorization': `Bearer ${store.token}`,
      },
    });
    console.log('Booking rooms response:', bookingResponse.data); // Log data untuk verifikasi
    if (Array.isArray(bookingResponse.data.data)) {
      const bookings = bookingResponse.data.data;
      bookingRooms.value = bookings.length;

      // Filter booking berdasarkan tipe kamar
      singleRoomBookings.value = bookings.filter(booking => booking.type === 'Single Room').length;
      standardRoomBookings.value = bookings.filter(booking => booking.type === 'Standard Room').length;
      doubleRoomBookings.value = bookings.filter(booking => booking.type === 'Double Room').length;
      deluxeRoomBookings.value = bookings.filter(booking => booking.type === 'Deluxe Room').length;
    }
  } catch (error) {
    console.error('Error fetching booking data:', error);
  }
}



</script>
