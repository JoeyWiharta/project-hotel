<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import alertify from 'alertifyjs'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/counter'

// Setup variables and store
const rooms = ref([])
const store = useUserStore()
const router = useRouter()

console.log('Stored Token:', store.token) // Log token for debugging

// Set customConfig to include token when store.token is available
const customConfig = ref({
  'Authorization': `Bearer ${store.token}`,
  'Content-Type': 'application/json',
})

console.log('Authorization Header:', customConfig.value) // Log header for debugging

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
    console.log('Response Data:', response.data) // Only for development
    rooms.value = response.data.data // Ensure this matches the structure of your API response
  }).catch(error => {
    console.log('AJAX Error:', error)
    alertify.error('Failed to fetch rooms: Unauthorized')
  })
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
</script>

<template>
  <div class="rounded-3" style="width: 100%; height: 100%; padding-top: 70px;">
    <div class="container-fluid">
      <h1 class="pt-4" style="color: #66A5AD;">DAFTAR ROOM</h1>
      <div>
        <button type="button" class="btn btn-primary" @click="addRoom">
          Add Room
        </button>
      </div>

      <table class="table table-striped table-light mt-4">
        <thead>
          <tr class="table-dark">
            <th class="text-center">Room Number</th>
            <th class="text-center">Type</th>
            <th class="text-center">Price per Night (Rp)</th>
            <th class="text-center">Description</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(room, index) in rooms" :key="room.roomnumber">
            <td class="text-center">{{ room.roomnumber }}</td>
            <td class="text-center">{{ room.type }}</td>
            <td class="text-center">{{ room.price_per_night }}</td>
            <td class="text-center">{{ room.description }}</td>
            <td class="text-center">{{ room.status }}</td>
            <td class="text-center">
              <button type="button" class="btn btn-outline-success btn-sm" @click="editRoom(room.roomnumber)">
                Edit
              </button>
              <button type="button" @click="deleteDialog(room.roomnumber)" class="btn btn-outline-danger btn-sm">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
