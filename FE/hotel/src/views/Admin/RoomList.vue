<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import alertify from 'alertifyjs'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/counter'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Setup variables and store
const rooms = ref([]) // Semua data rooms dari API
const filteredRooms = ref([]) // Data rooms setelah difilter
const filterOption = ref('All') // Pilihan filter saat ini (default: All)

const store = useUserStore()
const router = useRouter()

const customConfig = ref({
  'Authorization': `Bearer ${store.token}`,
  'Content-Type': 'application/json',
})

// Fetch room data on mount
onMounted(() => {
  refreshData()
})

// Function to refresh room data
function refreshData() {
  axios({
    url: 'http://localhost:8000/api/rooms',
    method: 'get',
    headers: customConfig.value
  }).then(response => {
    rooms.value = response.data.data // Pastikan ini sesuai struktur API
    applyFilter() // Terapkan filter setelah data diambil
  }).catch(error => {
    console.log('AJAX Error:', error)
    alertify.error('Failed to fetch rooms: Unauthorized')
  })
}

// Function to apply the selected filter
function applyFilter() {
  if (filterOption.value === 'All') {
    filteredRooms.value = rooms.value
  } else {
    filteredRooms.value = rooms.value.filter(room => room.type === filterOption.value)
  }
}

// Function to confirm deletion
function deleteDialog(roomnumber) {
  alertify.confirm('Confirmation', 'Are you sure to delete this data?', 
    function() { 
      deleteRoom(roomnumber)
    }, function() { 
      // Do nothing if canceled
    }
  )
}

// Function to delete room
function deleteRoom(roomnumber) {
  axios({
    url: `http://localhost:8000/api/rooms/delete/${roomnumber}`,
    method: 'delete',
    headers: customConfig.value
  }).then(response => {
    if (response.data.success === true) {
      alertify.alert('Information', 'Data has been deleted!', function() { alertify.success('OK') })
      refreshData()
    } else {
      alertify.error('Failed to delete room')
    }
  }).catch(error => {
    console.log('AJAX Error:', error)
    alertify.error('Failed to delete room: Unauthorized')
  })
}

// Function to navigate to the add room form
function addRoom() {
  router.push('/rooms/add')
}

// Function to navigate to the edit room form
function editRoom(roomnumber) {
  router.push(`/rooms/edit/${roomnumber}`)
}

function formatPrice(price) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2, // Menampilkan dua digit desimal
    maximumFractionDigits: 2
  }).format(price);
}

</script>

<template>
  <div class="absolute right-0 w-5/6 px-4">
    <div class="rounded-3" style="width: 100%; height: 100%;">
      <div class="container-fluid">
        <div class="flex justify-between mt-4">
          <h1 class="pt-4" style="color: #1366D9;">ROOM MANAGEMENT</h1>
          <div class="mr-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512" class="ml-2 max-w-5 md:max-w-10">
            <path fill="#2c47d1" fill-rule="evenodd" d="M256 42.667A213.333 213.333 0 0 1 469.334 256c0 117.821-95.513 213.334-213.334 213.334c-117.82 0-213.333-95.513-213.333-213.334C42.667 138.18 138.18 42.667 256 42.667m21.334 234.667h-42.667c-52.815 0-98.158 31.987-117.715 77.648c30.944 43.391 81.692 71.685 139.048 71.685s108.104-28.294 139.049-71.688c-19.557-45.658-64.9-77.645-117.715-77.645M256 106.667c-35.346 0-64 28.654-64 64s28.654 64 64 64s64-28.654 64-64s-28.653-64-64-64"/>
          </svg>
            <p class="text-[#1366D9]">Admin</p>
      </div>
        </div>
        
        
        <div class="d-flex justify-content-between align-items-center mt-8">
          <!-- Dropdown for filtering -->
          <div class="dropdown">

              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                {{ filterOption }}
              </button>
            
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" href="#" @click="filterOption = 'All'; applyFilter()">All Rooms</a></li>
              <li><a class="dropdown-item" href="#" @click="filterOption = 'Single Room'; applyFilter()">Single</a></li>
              <li><a class="dropdown-item" href="#" @click="filterOption = 'Standard Room'; applyFilter()">Standard</a></li>
              <li><a class="dropdown-item" href="#" @click="filterOption = 'Double Room'; applyFilter()">Double</a></li>
              <li><a class="dropdown-item" href="#" @click="filterOption = 'Deluxe Room'; applyFilter()">Deluxe</a></li>
            </ul>

          </div>

            <button type="button" class="btn btn-primary p-2" @click="addRoom">
              <FontAwesomeIcon icon="plus"/>
              Add Room
            </button>
          
        </div>

      
        <table class="w-full mt-4 rounded-lg">
          <thead>
            <tr class="bg-[#1366D9] text-white text-xl">
              <th class="text-center">Room Number</th>
              <th class="text-center">Type</th>
              <th class="text-center">Price per Night</th>
              <th class="text-center">Description</th>
              <th class="text-center">Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(room, index) in filteredRooms" :key="room.roomnumber" class="bg-[#87A2FF] text-white text-lg font-poppins">
              <td class="text-center">{{ room.roomnumber }}</td>
              <td class="text-center">{{ room.type }}</td>
              <td class="text-center">{{ formatPrice(room.price_per_night) }}</td>
              <td class="text-center">{{ room.description }}</td>
              <td class="text-center">{{ room.status }}</td>
              <td class="text-center">
                <button type="button" class="p-3" @click="editRoom(room.roomnumber)">
                  <img src="../../assets/edit.svg" alt="" class="max-w-5 hover:bg-lime-600">
                </button>
                <button type="button" @click="deleteDialog(room.roomnumber)" class="p-3">
                  <img src="../../assets/delete.svg" alt="" class="max-w-5 hover:bg-red-500">
                </button>
              </td>
            </tr>
          </tbody>
        </table>


      </div>
    </div>
  </div>
</template>

<style scoped>
.table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 1rem;
  background-color: transparent;
}
.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}
.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
