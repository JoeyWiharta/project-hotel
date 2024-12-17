<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import alertify from 'alertifyjs'
import { useUserStore } from '@/stores/counter'

// Setup variables and store
const rooms = ref([])
const store = useUserStore()

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

// Function to group rooms by type
function groupRoomsByType() {
  return rooms.value.reduce((groups, room) => {
    const type = room.type || 'Unknown'
    if (!groups[type]) {
      groups[type] = []
    }
    groups[type].push(room)
    return groups
  }, {})
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
  <div class="absolute right-0 w-5/6">
    <div class="rounded-3" style="width: 100%; height: 100%;">
    <div class="container-fluid">
      <h1 class="pt-4" style="color: #1366D9;">VIEW ROOMS</h1>
      <div v-for="(group, type) in groupRoomsByType()" :key="type" class="mb-5">
        <h3>{{ type }}</h3>
        <div class="row">
          <div v-for="room in group" :key="room.roomnumber" class="col-md-4 mb-4 ">
            <div class="card ">
              <div class="card-body rounded-lg font-poppins">
                <h5 class="card-title">{{ room.roomnumber }}</h5>
                <h6 class="card-subtitle mb-2">{{ room.type }}</h6>
                <p class="card-text ">Price per Night: <br><span class="text-[#1366D9] text-xl font-semibolf">{{ formatPrice(room.price_per_night) }}</span>/Night</p>
                <p class="card-text">Description: {{ room.description }}</p>
                <p class="card-text">Status: <span :class="{'text-red-500': room.status === 'Unavailable', 'text-green-500': room.status === 'Available'}">{{ room.status }}</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  
</template>

<style scoped>
.card {
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.card-title {
  font-size: 1.5rem;
  font-weight: bold;
}
.card-subtitle {
  font-size: 1rem;
}
.card-text {
  font-size: 0.9rem;
}
</style>
