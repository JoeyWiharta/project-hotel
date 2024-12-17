<template>
  <div class="absolute right-0 w-5/6 pl-10 bg-[#F4F6FF] min-h-screen">
    <header>
      <div class="position-absolute top-0 end-0 mx-1 mt-2">
        <div class="ms-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512">
            <path fill="#2c47d1" fill-rule="evenodd" d="M256 42.667A213.333 213.333 0 0 1 469.334 256c0 117.821-95.513 213.334-213.334 213.334c-117.82 0-213.333-95.513-213.333-213.334C42.667 138.18 138.18 42.667 256 42.667m21.334 234.667h-42.667c-52.815 0-98.158 31.987-117.715 77.648c30.944 43.391 81.692 71.685 139.048 71.685s108.104-28.294 139.049-71.688c-19.557-45.658-64.9-77.645-117.715-77.645M256 106.667c-35.346 0-64 28.654-64 64s28.654 64 64 64s64-28.654 64-64s-28.653-64-64-64"/>
          </svg>
        </div>
        <p class="text-primary me-4">Admin</p>
      </div>
    </header>

    <div class="container-fluid">
      <h1 class="pt-4 mt-4" style="color: #1366D9;">DATABASE</h1>

      <div class="flex justify-between w-full mt-14">
        <div class="flex w-1/3">
          <input type="text" v-model="cari" placeholder="Cari booking..." class="w-full rounded-lg p-2 me-2 shadow-xl" />
          <button @click="docari" class="btn btn-primary shadow-xl">Cari</button>
        </div>
        
        <div class="w-1/3 flex justify-end">
          <select v-model="per_page" class="w-1/2 shadow-xl rounded-lg" @change="refreshdata">
          <option value="5">5 per page</option>
          <option value="10">10 per page</option>
          <option value="15">15 per page</option>
          <option value="20">20 per page</option>
        </select>
        </div>  
      </div>

      <table class="w-full mt-8">
        <thead>
          <tr class=" bg-[#1366D9] text-white text-md font-poppins text-lg">
            <th class="text-center">No</th>
            <th class="text-center">Username</th>
            <th class="text-center">Email</th>
            <th class="text-center">Phone</th>
            <th class="text-center">Room</th>
            <th class="text-center">Room Type</th>
            <th class="text-center">Check-in Date</th>
            <th class="text-center">Check-out Date</th>
            <th class="text-center">Status</th>
            <th class="text-center">Total Price (Rp)</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(booking, index) in bookings" :key="booking.id"  class="bg-[#87A2FF] text-white text-md font-poppins text-lg">
            <td class="text-center">{{ nomor + index }}</td>
            <td class="text-center">{{ booking.user_name }}</td>
            <td class="text-center">{{ booking.email }}</td>
            <td class="text-center">{{ booking.phone_number }}</td>
            <td class="text-center">{{ booking.roomnumber }}</td>
            <td class="text-center">{{ booking.type }}</td>
            <td class="text-center">{{ booking.checkin_date }}</td>
            <td class="text-center">{{ booking.checkout_date }}</td>
            <td class="text-center">{{ booking.status }}</td>
            <td class="text-center">{{ booking.total_price }}</td>
            <td class="text-center">
              <button type="button" @click="deleteDialog(booking.id)" class="btn btn-outline-danger btn-sm">
                Check-Out
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination Controls -->
      <div class="d-flex justify-content-center mt-3 gap-2">
        <button 
          class="btn btn-outline-primary me-1" 
          :disabled="currentPage === 1" 
          @click="goToPage(currentPage - 1)">
          Previous
        </button>

        <button 
          v-for="page in pages" 
          :key="page" 
          class="btn" 
          :class="{'btn-primary': page === currentPage, 'btn-outline-primary': page !== currentPage}" 
          @click="goToPage(page)">
          {{ page }}
        </button>

        <button 
          class="btn btn-outline-primary ms-1" 
          :disabled="currentPage === totalPages" 
          @click="goToPage(currentPage + 1)">
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, computed } from 'vue';
import alertify from 'alertifyjs';
import { useUserStore } from '@/stores/counter';

export default {
  name: 'AdminDatabase',
  setup() {
    const store = useUserStore();
    const bookings = ref([]);
    const ncount = ref(0); // Total data count from API
    const per_page = ref(5); // Number of items per page
    const currentPage = ref(1); // Current page number
    const cari = ref(''); // Search keyword

    // Compute total pages
    const totalPages = computed(() => Math.ceil(ncount.value / per_page.value));

    // Compute start number for pagination
    const start = computed(() => (currentPage.value - 1) * per_page.value);

    // Row number offset
    const nomor = computed(() => start.value + 1);

    // Generate page numbers for pagination
    const pages = computed(() => {
      let pageNumbers = [];
      for (let i = 1; i <= totalPages.value; i++) {
        pageNumbers.push(i);
      }
      return pageNumbers;
    });

    // Fetch data from API
    async function refreshdata() {
      let start_data = start.value;
      let thedata = { start: start_data, limit: per_page.value, cari: cari.value };

      try {
        const customConfig = {
          headers: {
            'Authorization': `Bearer ${store.token}`,
            'Content-Type': 'application/json',
          }
        };
        const response = await axios.post('http://localhost:8000/api/bookings/search', thedata, customConfig);
        console.log('Response Data:', response.data); // Log data untuk verifikasi
        bookings.value = response.data.bookings;
        ncount.value = response.data.count; // Update total items
      } catch (error) {
        console.error('Error fetching booking data:', error);
      }
    }

    // Navigate to a specific page
    function goToPage(page) {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        refreshdata();
      }
    }

    // Function for searching bookings
    function docari() {
      currentPage.value = 1;
      refreshdata();
    }

    // Delete dialog placeholder
    function deleteDialog(bookingId) {
      alertify.confirm('Confirmation', 'Are you sure to delete this booking?', 
        function() { 
          deleteBooking(bookingId);
        }, function() { 
          // Do nothing if canceled
        }
      );
    }

    async function deleteBooking(bookingId) {
      try {
        const customConfig = {
          headers: {
            'Authorization': `Bearer ${store.token}`,
            'Content-Type': 'application/json',
          }
        };
        const response = await axios.delete(`http://localhost:8000/api/bookings/admin/delete/${bookingId}`, customConfig);
        console.log('Delete Response:', response.data); // Log data untuk verifikasi
        if (response.data.success === true) {
          alertify.alert('Information', 'Booking has been checked out!', function() { alertify.success('OK') });
          await refreshdata(); // Refresh data setelah penghapusan
        } else {
          alertify.error('Failed to check out booking');
        }
      } catch (error) {
        console.error('Error deleting booking:', error);
        alertify.error('Failed to check out booking');
      }
    }

    // Fetch initial data
    refreshdata();

    return {
      bookings,
      ncount,
      per_page,
      currentPage,
      totalPages,
      pages,
      nomor,
      cari,
      refreshdata,
      deleteDialog,
      goToPage,
      docari,
    };
  }
};
</script>

<style> .table { margin-top: 20px; width: 100%; table-layout: fixed; /* Membuat lebar tabel tetap dan tidak melar / } th, td { text-align: center; padding: 10px; / Menambahkan padding agar kolom lebih rapi / word-wrap: break-word; / Memastikan teks tidak keluar dari kolom / } th { width: 15%; / Menentukan lebar kolom header agar tidak terlalu besar / } td { width: 15%; / Menentukan lebar kolom agar lebih seimbang / } / Responsif untuk layar kecil / @media (max-width: 768px) { .table { font-size: 12px; / Menyesuaikan ukuran font pada layar kecil / } th, td { padding: 8px; / Mengurangi padding agar tetap proporsional di layar kecil / } } .btn-primary { background-color: #007bff; color: white; } .btn-outline-success, .btn-outline-danger { font-size: 0.875rem; / Mengatur ukuran font tombol agar proporsional */ } </style>