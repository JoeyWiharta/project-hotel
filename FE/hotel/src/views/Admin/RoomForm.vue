<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import alertify from 'alertifyjs'
import { useUserStore } from '@/stores/counter'

// Setup variables and store
const roomnumber_readonly = ref(false)
const route = useRoute()
const router = useRouter()
const store = useUserStore()

const roomnumber = ref('')
const type = ref('')
const price_per_night = ref('')
const description = ref('')
const status = ref('Available')

const customConfig = {
  'Content-Type': 'application/json',
  'Authorization': `Bearer ${store.token}`
}

const thedata = computed(() => ({
  roomnumber: roomnumber.value,
  type: type.value,
  price_per_night: price_per_night.value,
  description: description.value,
  status: status.value
}))

// Function to save room data
function save() {
  const store_or_update = route.params.roomnumber !== undefined ? `update/${route.params.roomnumber}` : 'create'
  axios({
    url: `http://localhost:8000/api/rooms/${store_or_update}`,
    method: 'post',
    data: thedata.value,
    headers: customConfig
  }).then(response => {
    console.log(response.data) // Only for development
    if (response.data.success === true) {
      alertify.alert('Information', 'Data has been saved!', function() { alertify.success('OK') })
      router.push('/rooms')
    }
  }).catch(error => {
    console.log('AJAX Error:', error)
  })
}

// Function to cancel and go back to room list
function cancel() {
  router.push('/rooms')
}

// Fetch room data if editing
onMounted(() => {
  if (route.params.roomnumber !== undefined) {
    roomnumber_readonly.value = true
    axios({
      url: `http://localhost:8000/api/rooms/${route.params.roomnumber}`,
      method: 'get',
      headers: customConfig
    }).then(response => {
      console.log('Response Data:', response.data) // Only for development
      if (response.data.success === true) {
        const data = response.data.data
        roomnumber.value = data.roomnumber
        type.value = data.type
        price_per_night.value = data.price_per_night
        description.value = data.description
        status.value = data.status
      }
    }).catch(error => {
      console.log('AJAX Error:', error)
    })
  }
})
</script>

<template>
  <div class="w-4/6 absolute right-0 flex justify-center items-center mr-32 mt-20 bg-[#F4F6FF] border-2 shadow-lg p-10 rounded-xl">
    <div class="w-full">
      <h2 class="font-poppins">{{ route.params.roomnumber !== undefined ? 'Edit' : 'Add' }} Room</h2>
    <form @submit.prevent="save">
      <div class="form-group mt-4">
        <label for="roomnumber">Room Number:</label>
        <input type="text" class="form-control" id="roomnumber" v-model="roomnumber" :readonly="roomnumber_readonly" required>
      </div>
      <div class="form-group">
        <label for="type">Type:</label>
        <select class="form-control" id="type" v-model="type" required>
          <option value="Single Room">Single Room</option>
          <option value="Double Room">Double Room</option>
          <option value="Deluxe Room">Deluxe Room</option>
          <option value="Standard Room">Standard Room</option>
        </select>
      </div>
      <div class="form-group">
        <label for="price_per_night">Price per Night (Rp):</label>
        <input type="number" class="form-control" id="price_per_night" v-model="price_per_night" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <input type="text" class="form-control" id="description" v-model="description">
      </div>
      <div class="form-group">
        <label for="status">Status:</label>
        <select class="form-control" id="status" v-model="status">
          <option value="Available">Available</option>
          <option value="Unavailable">Unavailable</option>
        </select>
      </div>
      <div class="d-flex flex-row justify-content-center gap-2">
        <button type="submit" class="btn btn-success mt-3 btn-lg">Save</button>
        <button type="button" class="btn btn-danger mt-3 ms-2 btn-lg" @click="cancel">Cancel</button>
      </div>
    </form>
    </div>
  </div>
</template>

<style scoped>
.form-group {
  margin-bottom: 15px;
}
button {
  margin-top: 10px;
}
</style>
