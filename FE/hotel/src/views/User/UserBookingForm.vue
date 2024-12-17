<template>
  <div class="booking-form mt-24 shadow-2xl mx-16 lg:mx-60 rounded-xl">
    <h2 class="text-center mt-4">Booking Form</h2>
    <h3 class="text-center"> Room {{ roomnumber }}</h3>
    <form @submit.prevent="submitBooking">
      <div class="w-full mt-8">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" readonly class="border-2 rounded-lg p-2 mt-2"/>
      </div>
      <div class="w-full mt-4">
        <label for="roomnumber">Room Number</label>
        <input type="text" id="roomnumber" v-model="roomnumber" readonly class="border-2 rounded-lg p-2 mt-2"/>
      </div>
      <div class="w-full mt-4">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" v-model="phone" required class="border-2 rounded-lg p-2 mt-2"/>
      </div>
      <div class="flex mt-4 w-full">
          <div class="w-1/2">
          <label for="checkin_date">Check-in Date</label>
          <input type="date" id="checkin_date" v-model="checkin_date" required class="border-2 rounded-lg p-2 mt-2"/>
        </div>
        <div class="w-1/2">
          <label for="checkout_date">Check-out Date</label>
          <input type="date" id="checkout_date" v-model="checkout_date" required class="border-2 rounded-lg p-2 mt-2"/>
        </div>
      </div>
      
      <div class="button-group flex justify-center mt-14">
        <button type="button" @click="cancelBooking" class="rounded-xl text-lg mr-10">Cancel</button>
        <button type="submit" class="bg-[#3883C8] text-white rounded-xl text-lg">Book Room</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useUserStore } from '@/stores/counter';

const route = useRoute();
const router = useRouter();
const store = useUserStore();
const username = ref(store.username || '');
const email = ref(store.email || '');
const roomnumber = ref(route.query.roomnumber || '');
const phone = ref('');
const checkin_date = ref('');
const checkout_date = ref('');

// Function to submit booking form
async function submitBooking() {
  try {
    const response = await axios.post(`http://localhost:8000/api/bookings/create/${roomnumber.value}`, {
      username: username.value,
      email: email.value,
      phone_number: phone.value,
      checkin_date: checkin_date.value,
      checkout_date: checkout_date.value,
    }, {
      headers: {
        'Authorization': `Bearer ${store.token}`,
        'Content-Type': 'application/json',
      }
    });
    if (response.data.success) {
      alert('Booking successful!');
      router.push('/user-booking'); // Navigate back to booking page
    } else {
      alert('Booking failed: ' + response.data.message);
    }
  } catch (error) {
    console.error('Error submitting booking:', error);
    alert('Booking failed');
  }
}

// Function to cancel booking and navigate back to booking page
function cancelBooking() {
  router.push('/user-booking');
}
</script>

<style scoped>
.booking-form {
  padding: 20px;
}
form div {
  margin-bottom: 10px;
}
label {
  display: block;
}
input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}
.button-group {
  display: flex;
  gap: 10px;
}
button {
  padding: 10px 20px;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background-color: #0056b3;
}
button[type="button"] {
  background-color: #6c757d;
}
button[type="button"]:hover {
  background-color: #5a6268;
}
</style>