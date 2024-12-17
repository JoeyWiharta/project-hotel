<template>
    <div class="user-booking-page mt-20 w-full h-auto text-center ">
      <h1 class="font-poppins">Choose Room</h1>
      <p class="mb-16">There are various types of rooms that can be selected according to your criteria</p>
      
      <div class="room-categories gap-10 flex flex-wrap justify-center h-auto p-10">

        <div class="room-category bg-white shadow-lg lg:w-1/3 w-full ">
          <h3 class="font-poppins">Single Room</h3>
          <div class="flex justify-start flex-wrap w-full gap-4  mt-4">
            <button 
              v-for="room in roomsGroupedByType['Single Room']" 
              :key="room.id" 
              :class="{ selected: selectedRoom === room.roomnumber }" 
              @click="toggleRoomSelection(room.roomnumber)" >
              {{ room.roomnumber.replace('Room', 'R.') }}
            </button>
          </div>
          <div  class="w-full flex justify-center">
            <p v-if="!roomsGroupedByType['Single Room'].length">No rooms available</p>
            <button class="book-now mt-10 w-auto text-xl border-2 rounded-full border-slate-700" v-if="roomsGroupedByType['Single Room'].length" @click="goToBookingForm()">Book Now</button>
          </div>
        </div>
  
        <div class="room-category  bg-white shadow-lg lg:w-1/3 w-full">
          <h3 class="font-poppins">Standard Room</h3>
          <div class=" flex justify-start flex-wrap w-full gap-4  mt-4">
            <button 
              v-for="room in roomsGroupedByType['Standard Room']" 
              :key="room.id" 
              :class="{ selected: selectedRoom === room.roomnumber }" 
              @click="toggleRoomSelection(room.roomnumber)">
              {{ room.roomnumber.replace('Room', 'R.') }}
            </button>
          </div>
          <div class="w-full flex justify-center">
            <p v-if="!roomsGroupedByType['Standard Room'].length">No rooms available</p>
            <button class="book-now mt-10 w-auto text-xl" v-if="roomsGroupedByType['Standard Room'].length" @click="goToBookingForm()">Book Now</button>
          </div>
        
        </div>
  
        <div class="room-category w-full lg:w-1/3 bg-white shadow-lg h-auto ">
          <h3 class="font-poppins">Double Room</h3>
          <div class="flex justify-start flex-wrap w-full gap-4  mt-4">
            <button 
              v-for="room in roomsGroupedByType['Double Room']" 
              :key="room.id" 
              :class="{ selected: selectedRoom === room.roomnumber }" 
              @click="toggleRoomSelection(room.roomnumber)">
              {{ room.roomnumber.replace('Room', 'R.') }}
            </button>
          </div>
          <div class="w-full flex justify-center">
            <p v-if="!roomsGroupedByType['Double Room'].length">No rooms available</p>
            <button class="book-now mt-10 w-auto text-xl" v-if="roomsGroupedByType['Double Room'].length" @click="goToBookingForm()">Book Now</button>
          </div>
         
        </div>
  
        <div class="room-category w-full lg:w-1/3 bg-white shadow-lg">
          <h3 class="font-poppins">Deluxe Room</h3>
          <div class="flex justify-start flex-wrap w-full gap-4 mt-4">
            <button 
              v-for="room in roomsGroupedByType['Deluxe Room']" 
              :key="room.id" 
              :class="{ selected: selectedRoom === room.roomnumber }" 
              @click="toggleRoomSelection(room.roomnumber)">
              {{ room.roomnumber.replace('Room', 'R.') }}
            </button>
          </div>
          <div class="w-full flex justify-center">
            <p v-if="!roomsGroupedByType['Deluxe Room'].length">No rooms available</p>
            <button class="book-now mt-10 w-auto text-xl" v-if="roomsGroupedByType['Deluxe Room'].length" @click="goToBookingForm()">Book Now</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import { useUserStore } from '@/stores/counter';
  
  // Define reactive variables
  const rooms = ref({
    'Single Room': [],
    'Standard Room': [],
    'Double Room': [],
    'Deluxe Room': []
  });
  const selectedRoom = ref(null);  // To store the selected room
  const store = useUserStore();
  const router = useRouter();
  const customConfig = ref({
    'Authorization': `Bearer ${store.token}`,
    'Content-Type': 'application/json',
  });
  
  // Fetch room data on mount
  onMounted(() => {
    fetchRoomsByType('Single Room');
    fetchRoomsByType('Standard Room');
    fetchRoomsByType('Double Room');
    fetchRoomsByType('Deluxe Room');
  });
  
  // Function to fetch room data by type
  async function fetchRoomsByType(type) {
    try {
      const response = await axios.get('http://localhost:8000/api/rooms', {
        headers: customConfig.value,
        params: {
          type: type,
          status: 'Available'
        }
      });
      console.log(`Response Data for ${type}:`, response.data); // Log response for debugging
      rooms.value[type] = response.data.data;
    } catch (error) {
      console.error(Error `fetching room data for type ${type}:`, error);
    }
  }
  
  // Function to group rooms by type
  const roomsGroupedByType = computed(() => {
    return rooms.value;
  });
  
  // Function to toggle room selection
  function toggleRoomSelection(roomnumber) {
    selectedRoom.value = (selectedRoom.value === roomnumber) ? null : roomnumber;
  }
  
  // Function to navigate to BookingForm with selected roomnumber
  function goToBookingForm() {
    if (selectedRoom.value) {
      router.push({ name: 'booking-form', query: { roomnumber: selectedRoom.value } });
    } else {
      alert('Please select a room first.');
    }
  }
  </script>
  
  <style scoped>
  .user-booking-page {
    padding: 20px;
  }
  .room-categories {
    display: flex;
    overflow-x: auto;
  }
  .room-category {
    flex: 0 0 auto;
    padding: 20px;
    margin-right: 20px;
    background-color: #e9aeae;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  button {
    width: 50px;
    height: 50px;
    padding: 10px;
    border-radius: 100%;
    cursor: pointer;
    border: 2px solid;
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  button.selected {
    background-color: #3883C8;
  }

  button.book-now {
    border-radius: 5px;
    background-color: #ffff ;
  }
  
  button.book-now:hover {
    background-color: #218838;
  }
  </style>